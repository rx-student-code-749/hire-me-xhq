    <?php session_start();

    require_once __DIR__ . "/.conf/definitions.php";
    require_once __DIR__ . "/../vendor/autoload.php";

<<<<<<< HEAD
$templateEngine = new \App\System\xTemplate(__DIR__ . "/../res/html");
$JSONResponse = new \Namacode\Utility\Responses\JSONResponse();

set_exception_handler(function (/** @var Exception $e*/$e) {
    global $JSONResponse;

    $JSONResponse->addNamedErrorMessage('message', $e->getMessage());
    $JSONResponse->addNamedErrorMessage('file', $e->getFile());
    $JSONResponse->addNamedErrorMessage('line', $e->getLine());
    $JSONResponse->setStatus(500);
    $JSONResponse->respond();
});
set_error_handler(function ($lvl, $msg, $file, $line, $context) {
    global $JSONResponse;

    $JSONResponse->addNamedErrorMessage('message', $msg);
    $JSONResponse->addNamedErrorMessage('line', $line);
    $JSONResponse->addNamedErrorMessage('file', $file);
    $JSONResponse->setStatus(500);
});

require_once CONFIG_DIR__TRAILING_SLASH . "redbean.conf.php";
if (array_key_exists('uid', $_SESSION))
    $GLOBALS['user'] = \App\Models\User::findByID($_SESSION['uid']);
=======
    $templateEngine = new \App\System\xTemplate(__DIR__ . "/../res/html");
    $JSONResponse = new \Namacode\Utility\Responses\JSONResponse();

    set_exception_handler(function (/** @var Exception $e*/$e) {
        global $JSONResponse;

        $JSONResponse->addNamedErrorMessage('message', $e->getMessage());
        $JSONResponse->addNamedErrorMessage('file', $e->getFile());
        $JSONResponse->addNamedErrorMessage('line', $e->getLine());
        $JSONResponse->setStatus(500);
        $JSONResponse->respond();
    });
    set_error_handler(function ($lvl, $msg, $file, $line, $context) {
        global $JSONResponse;

        $JSONResponse->addNamedErrorMessage('message', $msg);
        $JSONResponse->addNamedErrorMessage('line', $line);
        $JSONResponse->addNamedErrorMessage('file', $file);
        $JSONResponse->setStatus(500);
    });

    require_once CONFIG_DIR__TRAILING_SLASH . "redbean.conf.php";
    if (array_key_exists('uid', $_SESSION))
        $GLOBALS['user'] = \App\Models\User::findByID($_SESSION['uid']);
>>>>>>> ccf2bcfefd6d10c8562f32f284687310b2a9d65c

