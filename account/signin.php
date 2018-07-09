<?php
require_once('../config.php');
global $C, $PAGE, $USER;
$PAGE->header();
$PAGE->title('Sign In');
$PAGE->backgroud_img(true);
$status_obj = new stdClass();
if (isset($_POST['register-submit'])) {
    $status_obj = $USER->process_signup();
    echo "<div class='alert alert-success'>$status_obj->message</div>";
} elseif (isset($_POST['login-submit'])) {
    $status_obj = $USER->process_signin();
    if ($status_obj->status) {
        $PAGE->redirect($C->wwwroot . '/views/dashboard.php');
    }else{
        echo "<div class='alert alert-success'>$status_obj->message</div>";
    }
}
include('../forms/sign_in_form.php');
$PAGE->add_style($C->wwwroot . '/account/style/sign_in.css');
$PAGE->footer();
$PAGE->add_script($C->wwwroot . '/account/script/sign_in.js');