<?php
namespace manager;

class page {
    public static function backgroud_img($visible){
        if($visible){
            global $C;
            self::add_style($C->wwwroot."/theme/resources/style/general.css");
        }
    }
    public static function add_style($style_path){
        if(isset($style_path)){
            echo '<link href="'.$style_path.'" rel="stylesheet">';
        }
    }
    public static function add_script($script_path){
        if(isset($script_path)){
            echo '<script src="'.$script_path.'"></script>';
        }
    }
    public static function title($title){
        if(isset($title)){
            echo '<title>'.$title.'</title>';
        }
    }

    public static function header(){
        global $C;
        require($C->dirroot.'/theme/includes/header.php');
    }
    public static function footer(){
        global $C;
        require($C->dirroot.'/theme/includes/footer.php');
    }
    public static function topnav(){
        global $C;
        require($C->dirroot.'/theme/includes/topnav.php');
    }
}