<?php
/**
 * Require all the global files here
 */
require('autoload.php');
require('constants.php');

/**
 * Initialize all the global variables
 */
global $PAGE, $DB, $USER, $LOG;
session_start();
$LOG = new \manager\log();
$PAGE = new \manager\page();
$DB = new \manager\db();
$USER = new \manager\user();

/**
 * Get all the configs form config table and assign to the global config
 * Prevents additional queries to the same table
 */
if (!isset($C->configs)) {
    $C->configs = \manager\config::get_all_configs();
}
