<?php

namespace App\Components;

class tools{

    public static function formatHtmlList($array){
        $result = '<ul>';
        foreach ($array as $value){
            $result .= '<li>'.$value.'</li>';
        }
        return $result."</ul>";
    }
}