<?php
require_once "../vendor/autoload.php";
session_start();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, ".env")->load();
