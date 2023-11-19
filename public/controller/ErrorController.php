<?php
class ErrorController{
    public static function Index(){
        require_once("./view/error/error404.phtml");
    }
    public static function Forbidden () {
        require_once "./view/error/error403.phtml";
    }
}