<?php
/**
 * This apps needs the following env vars:
 * DB_HOST
 * DB_DATABASE
 * DB_USER
 * DB_PASSWORD
 * APP_HOST (base url of the app)
 * 
 * optionals:
 * DEV (with any value)
 */
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
require_once "./autoload.php";
require_once "../config/config.php";
require_once "../config/Database.php";
require_once "./utils.php";

$HOST = (isset($_ENV["APP_HOST"]) ? $_ENV["APP_HOST"] :"http://localhost/");
$controller_name = "HomeController";
$method = "Index";
if (isset($_GET["controller"])) {
    $controller_name = ucfirst($_GET["controller"]) . "Controller";
    if (!class_exists($controller_name)) {
        $controller_name = "ErrorController";
    } else {
        if (isset($_GET["method"])) {
            $method = ucfirst($_GET["method"]);
            if (!method_exists($controller_name, $method)) {
                if (!method_exists($controller_name, "Index")) {
                    $controller_name = "ErrorController";
                    $method = "Index";
                } else
                    $method = "Index";
            }
        }
    }
    $controller = new $controller_name();
}

require_once "./view/layout/header.phtml";

$controller::$method();

require_once "./view/layout/footer.phtml";