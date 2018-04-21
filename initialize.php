<?php
require('autoload.php');
global $PAGE, $DB, $USER, $LOG;
//Initialize the global variables
$PAGE = new \manager\page();
$DB = new \manager\db();
$USER = new \manager\user();
$LOG = new \manager\log();
//Connect to database
$DB->connect();

