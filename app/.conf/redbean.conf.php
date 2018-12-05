<?php use RedBeanPHP\R;
$configuration = require_once __DIR__ . "/db.conf.php";
define( 'REDBEAN_MODEL_PREFIX', '\\App\\Models\\' );

R::setup("mysql:host={$configuration['host']};dbname={$configuration['name']}", $configuration['username'],  $configuration['password']);
R::fancyDebug(false);