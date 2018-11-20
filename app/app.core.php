<?php session_start();

require_once __DIR__ . "/.conf/definitions.php";
require_once __DIR__ . "/../vendor/autoload.php";

require_once CONFIG_DIR__TRAILING_SLASH . "redbean.conf.php";

$templateEngine = new \App\System\xTemplate(__DIR__ . "/../res/html");

if (isset($_SESSION['uid']))
    $GLOBALS['user'] = \App\Models\User::findByID($_SESSION['uid'])->box();
