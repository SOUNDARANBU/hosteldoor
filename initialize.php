<?php
require('autoload.php');
global $PAGE, $DB, $USER, $LOG;
session_start();
//Initialize the global variables
$LOG = new \manager\log();
$PAGE = new \manager\page();
$DB = new \manager\db();
$USER = new \manager\user();