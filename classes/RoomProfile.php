<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace de\peregrinus\progmatic;

require_once('Program.php');
require_once('Vacation.php');

/**
 * ProgMatic 2014 individual room profile
 *
 * @author Christoph
 */
class roomProfile
{
    /*
     * @var \string Title
     */
    protected $title;
    protected $data;
    protected $programs = array();
    protected $vacations = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        // sensible defaults:
        for ($i = 0; $i < 7; $i++) {
            $this->programs[$i] = new \de\peregrinus\progmatic\Program();
        }
        for ($i = 0; $i < 8; $i++) {
            $this->vacations[$i] = new \de\peregrinus\progmatic\Vacation();
        }
    }

    /**
     * Set the title
     * @param \string $title Title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the title
     * @return \string Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Import raw data
     * @param mixed $data Data
     */
    public function importRawData($data)
    {
        $data = unpack('A112days/A72vacation/c1flags/c1low/c1high/c1fill1/c1offset/c1offset2/A10fill2',
            $data);
        for ($i = 0; $i < 7; $i++) {
            $this->programs[$i]->importRawData(substr($data['days'], ($i * 16),
                    16));
        }
        unset($data['days']);
        for ($i = 0; $i < 8; $i++) {
            $this->vacations[$i]->importRawData(substr($data['vacation'],
                    ($i * 9), 9));
        }
        unset($data['vacation']);
        $this->data = $data;
    }

    /**
     * Export raw data for binary file
     * @return string Data
     */
    public function exportRawData()
    {
        $o = '';
        for ($j = 0; $j < 7; $j++) {
            $o.=$this->programs[$j]->exportRawData();
        }
        for ($j = 0; $j < 8; $j++) {
            $o.=$this->vacations[$j]->exportRawData();
        }

        $o .= pack('ccccccA10', $this->data['flags'], $this->data['low'],
            $this->data['high'], $this->data['fill1'], $this->data['offset'],
            $this->data['offset2'], $this->data['fill2']);

        return $o;
    }

    /**
     * Export raw title for binary file
     * @return string Data
     */
    public function exportRawTitle()
    {
        $title = str_replace(chr(0), '', $this->title);
        if (($title == '') || ($title == 'empty')) {
            $title = '###empty';
        }
        $title = str_pad($title, 32, '#');
        for ($i = 0; $i < strlen($title); $i++) {
            $o .= substr($title, $i, 1).chr(0);
        }
        return $o;
    }

    /**
     * Get a specific program
     * @param int $index Index
     * @return \de\peregrinus\progmatic\Program Program
     */
    public function getProgram($index)
    {
        return $this->programs[$index];
    }

    /**
     * Set a specific program
     * @param int $index
     * @param \de\peregrinus\progmatic\Program $program
     * @return \de\peregrinus\progmatic\roomProfile
     */
    public function setProgram($index, \de\peregrinus\progmatic\Program $program)
    {
        $this->programs[$index] = $program;
        return $this;
    }

    /**
     * Get a specific vacation
     * @param int $index Index
     * @return \de\peregrinus\progmatic\Vacation Vacation
     */
    public function getVacation($index)
    {
        return $this->vacations[$index];
    }

    /**
     * Set a specific vacation
     * @param int $index
     * @param \de\peregrinus\progmatic\Vacation Vacation
     * @return \de\peregrinus\progmatic\roomProfile
     */
    public function setVacation($index,
                                \de\peregrinus\progmatic\Vacation $vacation)
    {
        $this->vacations[$index] = $vacation;
        return $this;
    }

    /**
     * Get whole data array
     * @return array
     */
    function getData()
    {
        return $this->data;
    }

    /**
     * Set whole data array
     * @param array $data
     * @return \de\peregrinus\progmatic\ProgramItem
     */
    function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get specific data field
     * @param string $field Field name
     * @return array
     */
    function getDataField($field)
    {
        return $this->data[$field];
    }

    /**
     * Set specific data field
     * @param string $field Field name
     * @param array $data Data
     * @return \de\peregrinus\progmatic\ProgramItem
     */
    function setDataField($field, $data)
    {
        $this->data[$field] = $data;
        return $this;
    }
}