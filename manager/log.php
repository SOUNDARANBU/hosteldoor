<?php
namespace manager;

class log {
    private static $file;
    private static $handle;
    private static function initiate(){
        global $C;
        self::$file = $C->dirroot.'/log.txt';
        self::$handle = fopen(self::$file, 'a') or die('Cannot Open file'. self::$file);
    }

    public static function write_log($log_message){
        self::initiate();
        if(isset($log_message)){
            fwrite(self::$handle,"\n".date('d M,Y h:i:s').': '.$log_message);
        }
    }
}