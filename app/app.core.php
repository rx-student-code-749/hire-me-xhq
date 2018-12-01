<?php session_start();

require_once __DIR__ . "/.conf/definitions.php";
require_once __DIR__ . "/../vendor/autoload.php";

$templateEngine = new \App\System\xTemplate(__DIR__ . "/../res/html");
$JSONResponse = new \Namacode\Utility\Responses\JSONResponse();

set_exception_handler(function (Exception $e) {
    global $JSONResponse;

    $JSONResponse->setErrorMessage($e->getMessage());
    $JSONResponse->setStatus(501);
    $JSONResponse->respond();
});

require_once CONFIG_DIR__TRAILING_SLASH . "redbean.conf.php";
if (array_key_exists('uid', $_SESSION))
    $GLOBALS['user'] = \App\Models\User::findByID($_SESSION['uid']);

