<?php
unset($C);
// $C -> Configs
global $C;
$C = new stdClass();
//environment
$C->environment = 'development';

//db config
$C->db_connection_type= 'pdo';
$C->db_type = 'mysql';
$C->db_name = 'hosteldoor';
$C->db_host = "localhost";
$C->db_username = "root";
$C->db_password = "P@ssw0rd";
$C->db_table_prefix = 'hdr';

//Path Config
$C->wwwroot = 'http://hosteldoor.com';
$C->dirroot = 'D:\\portal\\hosteldoor-github';

//Site Config
$C->site_name = 'Hostel Door';
$C->site_desc = 'Hostel Management Application';
$C->favicon_url ='';

//SSL
$C->https = 0;

//SMTP
$C->Host = 'hostname';  // Specify main and backup SMTP servers
$C->SMTPAuth = true;         // Enable SMTP authentication
$C->Username = 'username';   // SMTP username
$C->Password = 'password';    // SMTP password
$C->SMTPSecure = 'ssl';              // Enable TLS encryption, `ssl` also accepted
$C->Port = 465;

//Initialize Application
require('initialize.php');
