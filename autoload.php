<?php
spl_autoload_register('autoload_classes');

function autoload_classes($classname){
    global $C;
    if(!isset($C->classes)){
        $Directory = new RecursiveDirectoryIterator($C->dirroot);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $objects = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        $C->classes = array();
        foreach($objects as $filepath => $arr){
            $filename = basename($filepath);
            $C->classes[$filename] = $filepath;
        }
    }

    //check for namespace
    $classname_pos = strpos($classname, "\\");
    if($classname_pos !== false){
        $classname = substr($classname, $classname_pos + 1);
    }

    $classfile = $classname . '.php';
    if(isset($C->classes[$classfile])){
        require $C->classes[$classfile];
    }
}