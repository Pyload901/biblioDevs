<?php
spl_autoload_register(function($classname) {
    include "./controller/" . $classname . ".php";
});