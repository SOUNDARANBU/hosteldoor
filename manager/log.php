<?php
namespace manager;

class log {
    private $file;
    private $handle;
    public function __construct($file_dir = null)
    {
        global $C;
        $this->file = isset($file_dir) ? $file_dir : $C->dirroot.'/log.txt';
        $this->handle = fopen($this->file, 'a') or die('Cannot Open file'. $this->file);
    }

    public function write_log($log_message){
        if(isset($log_message)){
            fwrite($this->handle,"\n".date('d M,Y h:i:s').': '.$log_message);
        }
    }
}