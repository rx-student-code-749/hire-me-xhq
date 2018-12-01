<?php session_start();

require_once __DIR__ . "/.conf/definitions.php";
require_once __DIR__ . "/../vendor/autoload.php";

require_once CONFIG_DIR__TRAILING_SLASH . "redbean.conf.php";

$templateEngine = new \App\System\xTemplate(__DIR__ . "/../res/html");
if (array_key_exists('uid', $_SESSION)) {
    $GLOBALS['user'] = \App\Models\User::findByID($_SESSION['uid']);
    /** @var \App\Models\User $user */ $user = $GLOBALS['user'];
}
