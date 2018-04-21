<?php
require_once('config.php');
global $DB;
$record = new stdClass();
$record->id = 1;
$record->name = "test";
$record->value = "afdsf";
$record->extra = "";

//var_dump($DB->get_records('config',['name' => 'helo']));

//var_dump($DB->get_records_sql("select * from config where name = ?",['name' => 'helo']));
//var_dump($DB->insert_record('config', $record));

//$DB->table_exists('configs');
//$DB->get_table_schema('config',true);

//var_dump($DB->update_record('config', (object)['id' => 1, 'name' => 'echo', 'value' => 'alskfj;lasdjf']));

//var_dump($DB->update_record_param('config', ['value' => 'value'], ['name' => 'helo']));

var_dump($DB->delete_records('config',[]));