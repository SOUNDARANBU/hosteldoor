<?php
namespace manager;

class page {

    public $title;
    public $background_img;
    public $style;
    public $script;
    public $header;
    public $footer;
    public $sidemenu;
    
    public function backgroud_img($visible){
        if($visible){
            global $C;
            $this->add_style($C->wwwroot."/theme/resources/style/general.css");
        }
    }
    public function add_style($style_path){
        if(isset($style_path)){
            echo '<link href="'.$style_path.'" rel="stylesheet">';
        }
    }
    public function add_script($script_path){
        if(isset($script_path)){
            echo '<script src="'.$script_path.'"></script>';
        }
    }
    public function title($title){
        if(isset($title)){
            echo '<title>'.$title.'</title>';
        }
    }

    public function header(){
        global $C;
        require($C->dirroot.'/theme/includes/header.php');
    }
    public function footer(){
        global $C;
        require($C->dirroot.'/theme/includes/footer.php');
    }
    public function topnav(){
        global $C;
        require($C->dirroot.'/theme/includes/topnav.php');
    }

    public function redirect($url, $params = [], $method = 'get'){
        header('Location:'. $url);
    }
}