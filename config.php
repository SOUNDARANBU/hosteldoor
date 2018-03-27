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
$C->db_password = "password";

//Path Config
$C->wwwroot = 'http://hosteldoor.com';
$C->dirroot = 'D:\\portal\\hosteldoor';

//Site Config
$C->site_name = 'Hostel Door';
$C->site_desc = 'Hostel Management Application';
$C->favicon_url ='';

//SSL
$C->https = 0;

require('autoload.php');
require('initialize.php');