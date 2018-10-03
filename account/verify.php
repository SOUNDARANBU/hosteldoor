<?php
require_once('../config.php');
global $C, $PAGE, $USER;
$PAGE->header();
$PAGE->title('Sign In');
$PAGE->backgroud_img(true);

$token = \manager\page::optional_param('token');

if($token){
    $decode = explode('_', base64_decode($token));
    //TODO: check the valid time


    //check if the user id valid
    $user = $DB->get_record('user', ['id' => $decode[1]]);
    if($user){
        $user->active = 1;
        $DB->update_record('user', $user);
    }


}


$PAGE->footer();