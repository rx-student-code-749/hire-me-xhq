<?php

use App\Models\User;

require_once __DIR__ . "/app/app.core.php";

$Executables = [

];
$JSONResponse = new \Namacode\Utility\Responses\JSONResponse();

if (!array_key_exists('action', $_GET)) {
    $JSONResponse->setErrorMsg("Action not defined!");
} else {
    $data = (array_key_exists('data', $_GET)) ? json_decode($_GET['data'], TRUE) : [];
    $JSONResponse->addUnique('data', $data);

    switch ($_GET['action']) {
        case 'isLoggedIn':
            $JSONResponse->addUnique('logged_in', false);
            break;
        case 'getHTML':
            if (array_key_exists('page', $data)) {
                $f = stream_resolve_include_path(__DIR__ . "/res/html/{$data['page']}.phtml");

                if (!$f)
                    $JSONResponse->setErrorMsg("Page not Found!");
                else
                    if (in_array(strtolower($data['page']), $Executables)) {

                    } else
                        $JSONResponse->addUnique('html', file_get_contents($f));
            } else $JSONResponse->setErrorMsg("Page not defined!");
            break;
        case 'login':
            $errs = [];

            if (!array_key_exists('username', $data) || $data['username'] === '')
                $errs['username'][] = "This is a required field.";
            if (!array_key_exists('password', $data) || $data['password'] === '')
                $errs['password'][] = "This is a required field.";

            if (!empty($errs))
                $this->JSONResponse->setErrors($errs);
            else {

                $bean = R::findOne(User::tableName(), "username = ?", [
                    $data['username']
                ]);


                if (!$bean)
                    $this->JSONResponse->addNamedErrorMsg('username', "Username not found!");
                else {
                    if (User::verifyPassword($data['password'], $bean->hash)) {
                        $_SESSION['uid'] = (int) $bean->id;
                        $this->JSONResponse->setSuccessMessage("User successfully logged in");
                    } else {
                        $this->JSONResponse->setErrorMsg("Username/Password combination not found.");
                    }
                }
            }

            break;
    }
}

//$req_dump = print_r($_REQUEST, TRUE);
//$fp = fopen('request.log', 'a');
//fwrite($fp, $req_dump);
//fclose($fp);
//$req_dump = print_r($JSONResponse->getResponse(), TRUE);
//$fp = fopen('response.log', 'a');
//fwrite($fp, $req_dump);
//fclose($fp);
$JSONResponse->respond();
