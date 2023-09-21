<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
define('MODELS',dirname(__DIR__).DIRECTORY_SEPARATOR.'Models' . DIRECTORY_SEPARATOR);
require 'src/routes.php';


// use Code;

// $code = new App\Code\Code();