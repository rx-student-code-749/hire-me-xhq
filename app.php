<?php

use App\Models\User;
use RedBeanPHP\R;

require_once __DIR__ . "/app/app.core.php";

$Executables = [

];
$JSONResponse = new \Namacode\Utility\Responses\JSONResponse();

if (!array_key_exists('action', $_GET)) {
    $JSONResponse->setErrorMsg("Action not defined!");
    $JSONResponse->setStatus(500);
} else {
    $data = (array_key_exists('data', $_GET)) ? json_decode($_GET['data'], TRUE) : [];

    $tmp = [];
    if (is_string($data))
        parse_str($data, $tmp);

    $data = $tmp ?: $data;
//    $JSONResponse->addUnique('data', $data);

    switch ($_GET['action']) {
        case 'isLoggedIn':
            $JSONResponse->addUnique('logged_in', isset ($_SESSION['uid']));
            break;
        case 'getHTML':
            if (array_key_exists('page', $data)) {
                $content = $templateEngine->render($data['page'], array_merge(
                    $GLOBALS,
                    (array_key_exists('vars', $data)) ? $data['vars'] : []
                ));

                if ($content === false || $content === \App\System\xTemplate::TEMPLATE_NOT_FOUND) {
                    $JSONResponse->setErrorMsg("Page not Found!");
                    $JSONResponse->setStatus(404);
                } else
                    $html = (!in_array($data['page'], [
                        'sidebar',
                        'job_details',
                        'user_profile'
                    ])) ? $templateEngine->render('layout', [
                        'title' =>
                            (array_key_exists('title', $GLOBALS))
                                ? $GLOBALS['title']
                                : ucwords(str_replace(['_'], [' '], $data['page'])),
                        'toolbar' =>
                            (array_key_exists('toolbar', $GLOBALS))
                                ? $GLOBALS['toolbar']
                                : "",
                        'content' => $content
                    ]) : $content;

                $JSONResponse->addUnique('html', $html);
                $JSONResponse->addUnique('title', (array_key_exists('title', $GLOBALS))
                    ? $GLOBALS['title']
                    : ucwords(str_replace(['_'], [' '], $data['page'])));

//                $f = stream_resolve_include_path(__DIR__ . "/res/html/{$data['page']}.phtml");
//
//                if (!$f)
//                    $JSONResponse->setErrorMsg("Page not Found!");
//                else
//                    if (in_array(strtolower($data['page']), $Executables)) {
//
//                    } else
//                        $JSONResponse->addUnique('html', file_get_contents($f));
            } else $JSONResponse->setErrorMsg("Page not defined!");
            break;
        case 'login':
            $errs = [];

            if (!array_key_exists('username', $data) || $data['username'] === '')
                $errs['username'][] = "This is a required field.";
            if (!array_key_exists('password', $data) || $data['password'] === '')
                $errs['password'][] = "This is a required field.";

            if (!empty($errs))
                $JSONResponse->setErrors($errs);
            else {

                $bean = (filter_var($data['username'], FILTER_VALIDATE_EMAIL))
                    ? R::findOne(User::tableName(), "email = ?", [
                        $data['username']
                    ]) : R::findOne(User::tableName(), "username = ?", [
                        $data['username']
                    ]);


                if (!$bean)
                    $JSONResponse->addNamedErrorMsg('username', "Username/Email not found!");
                else {
                    if (User::verifyPassword($data['password'], $bean->hash)) {
                        $_SESSION['uid'] = (int)$bean->id;
                        $JSONResponse->setSuccessMessage("User successfully logged in");
                    } else {
                        $JSONResponse->addNamedErrorMessage('password', "Incorrect Password.");
                    }
                }
            }

            break;
        case 'logout':
            session_destroy();
            $JSONResponse->setSuccessMessage('User successfully logged out.');
            break;
        case 'addUser':
            $user = User::newFromArray(array_merge(
                $data,
                [
                    'account_type'
                    => \App\Models\ENUM_UserAccountType::Get(\App\Models\ENUM_UserAccountType::STANDARD)
                ]
            ));

            if ($user->save()) {
                $JSONResponse->setSuccessMessage("{$user->getFullName()} added successfully!");
                $JSONResponse->addUnique('xid', $user->getID());
            } else
                $JSONResponse->setErrorMsg('An error occurred while creating the user. No changes saved.');
            break;
        case 'addJob':
            $data['category'] = \App\Models\LABEL_JobCategory::findByID($data['category']);
            $job = \App\Models\Job::newFromArray($data);

            if ($job->save()) {
                $JSONResponse->setSuccessMessage("Job successfully posted!");
                $JSONResponse->addUnique('xid', $job->id);
            } else
                $JSONResponse->setErrorMsg('An error occurred while creating the user. No changes saved.');
            break;
        case 'applyCancelJob':
            if (array_key_exists('uid', $_SESSION)) {
                $JSONResponse->addUnique('reach', true);
                $user = User::findByID($_SESSION['uid']);
                $job = \App\Models\Job::findByID($data['id'] ?: 1);
                if ($job->box()->appliedFor($user->id)) {
                    $JSONResponse->setErrorMessage('Cancel');
                } else {
                    $user->ownJobList[] = $job;
                    if ($user->box()->save())
                        $JSONResponse->setSuccessMessage('Job applied for successfully!');
                }

                $JSONResponse->addUnique('test[user]', $user);
                $JSONResponse->addUnique('test[job]', $job);
                $JSONResponse->addUnique('test[job->appliedFor]', $job->box()->appliedFor($user->id));
            }
            break;
        default:
            $JSONResponse->setErrorMsg('404.1 An error occurred!');
            $JSONResponse->setStatus(404);
    }
}

$req_dump = print_r($_REQUEST, TRUE);
$fp = fopen('request.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);
$req_dump = print_r($JSONResponse->getResponse(), TRUE);
$fp = fopen('response.log', 'a');
fwrite($fp, $req_dump);
fclose($fp);
$JSONResponse->respond();
