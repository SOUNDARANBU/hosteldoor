<?php
namespace manager;

class db {
    public static function connect() {
        global $C, $DB;
        if(isset($C->db_type) && isset($C->db_host)){
            try{
                //           $DB = new PDO("$C->db_type:host=$C->db_host;dbname=$C->db_name", $C->db_username, $C->db_password);
                // set the PDO error mode to exception
                //         $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              log::write_log('Database Connected');
            }catch (PDOException $e){
                //            writelog('Database Connection Failed');
            }
        }
    }
}