<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/10/2018
 * Time: 12:58 PM
 */

class user_renderer
{
    public static function render_status($status){
        $output_html = '';
        if($status == 0){
            $output_html .= '<span class="badge-pill badge-danger">Inactive</span>';
        }

        if($status == 1){
            $output_html .= '<span class="badge-pill badge-success">Active</span>';
        }

        return $output_html;
    }
}