<?php
//Šis fails tiek automātiski pievienots vairākās vietās tapēc veic duplikācijas pārbaudi.
if (!function_exists('getText')) {
    function Text($key)
    {
        return config("texts.{$key}");
    }
}