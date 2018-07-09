<?php
require_once('../config.php');
global $USER, $PAGE, $C;
if($USER->process_signout()){
    $PAGE->redirect($C->wwwroot. '/account/signin.php');
}