<?php
error_reporting('E_ALL');
ini_set('display_errors', 1);

require_once('lib/hex2bin.php');
require_once ('classes/Profile.php');

echo '<pre>Loading profile ...<br />';
//$profile = \de\peregrinus\progmatic\profile::fromFile('Profile5.dat');

$profile = new \de\peregrinus\progmatic\profile();
$profile->getRoomProfile(0)->setTitle('Lila Saal');
$profile->getRoomProfile(0)->getProgram(0)->getItem(0)->setDataManually(0, 1, 0,
    2);


//print_r($profile);

echo 'Saving to output/test.dat ...<br />';
$profile->toFile('output/test.dat');
echo '<a href="output/test.dat">Click here to download</a>';
