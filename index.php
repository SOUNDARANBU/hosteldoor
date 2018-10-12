<?php
require_once('config.php');
$USER->require_signin();

$PAGE->redirect($C->wwwroot . '/system/dashboard.php');
