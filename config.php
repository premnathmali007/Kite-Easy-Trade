<?php
require 'vendor/autoload.php';
/*********************************
 * Application specific variables *
 *********************************/
// Paths
$app_path_root 			= dirname(__FILE__) . "/";
$app_path_core 			= $app_path_root . 'core/';;
$app_path_class 		= $app_path_core . 'class/';
$app_path_templates 	= $app_path_root . 'core/templates/';
$app_path_assets		= 'assets/';
$app_path_js			= $app_path_assets . 'js/';
$app_path_css			= $app_path_assets . 'css/';

//Connect db
// Using Medoo namespace.
use Medoo\Medoo;

// Connect the database.
$database = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'kite_practice',
    'username' => 'prem',
    'password' => 'prem'
]);
?>