<?php
global $C, $PAGE;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1024">
<!--    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<? echo "$C->wwwroot/theme/resources/icon/favicon.png"; ?>">

    <!-- Bootstrap core CSS -->
    <?php $PAGE->add_style($C->wwwroot . "/theme/resources/style/bootstrap.min.css");
    $PAGE->add_style($C->wwwroot . "/theme/resources/style/theme.css");
    $PAGE->add_style($C->wwwroot . "/theme/resources/style/ionicons.min.css");
    //datatable css
    $PAGE->add_style($C->wwwroot . "/theme/resources/style/jquery.dataTables.min.css");
    $PAGE->add_style($C->wwwroot . "/theme/resources/style/select.dataTables.min.css");
//    $PAGE->add_style($C->wwwroot . "/theme/resources/style/buttons.dataTables.min.css");

    $PAGE->add_script($C->wwwroot . "/theme/resources/script/jquery-3.3.1.min.js");
    $PAGE->add_script($C->wwwroot . "/theme/resources/script/bootstrap.bundle.min.js");

    //datatablejs
    $PAGE->add_script($C->wwwroot . "/theme/resources/script/jquery.dataTables.min.js");
    $PAGE->add_script($C->wwwroot . "/theme/resources/script/dataTables.select.min.js");
    $PAGE->add_script($C->wwwroot . "/theme/resources/script/dataTables.buttons.min.js");
    ?>

    <!-- Custom styles for this template -->
</head>

<body class="bg">