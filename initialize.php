<?php
require('autoload.php');
global $PAGE, $DB, $USER, $LOG;
session_start();
//Initialize the global variables
$LOG = new \manager\log();
$PAGE = new \manager\page();
$DB = new \manager\db();
$USER = new \manager\user();


/*
 * DEFINE GLOBAL CONSTANTS HERE
 */

define("SUCCESS", 1);
define("FAIL", 0);