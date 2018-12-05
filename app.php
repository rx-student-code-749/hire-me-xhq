<?php

use App\Models\User;
use RedBeanPHP\R;

require_once __DIR__ . "/app/app.core.php";

$Executables = [

];
if (!array_key_exists('action', $_GET)) {
    $JSONResponse->setErrorMessage("Action not defined!");
    $JSONResponse->setStatus(500);
    $JSONResponse->addUnique('r', print_r($_REQUEST, TRUE));
    $JSONResponse->addUnique('g', print_r($_GET, TRUE));
} else {
    $data = [];
    if (array_key_exists('data', $_GET))
        $data = json_decode($_GET['data'], TRUE);
//    $data = (array_key_exists('data', $_GET)) ? json_decode($_GET['data'], TRUE) : [];

//    $JSONResponse->addUnique('rawData', $_GET['data']);
//    $JSONResponse->addUnique('sData', $data);

//    $tmp = [];
//    if (is_string($data))
//        parse_str($data, $tmp);
//
//    $data = empty($tmp) ? $data : $tmp;
//    $JSONResponse->addUnique('-data', $data);

    switch ($_GET['action']) {
        case 'isLoggedIn':
            $JSONResponse->addUnique('logged_in', isset ($_SESSION['uid']));
            $JSONResponse->setStatus(\Namacode\Utility\Responses\JSONResponse::STATUS_OK);
            break;
        case 'getHTML':
            if (array_key_exists('page', $data)) {
                $content = $templateEngine->render($data['page'], array_merge(
                    $GLOBALS,
                    (array_key_exists('vars', $data)) ? $data['vars'] : []
                ));

                if ($content === false || $content === \App\System\xTemplate::TEMPLATE_NOT_FOUND) {
                    $JSONResponse->setErrorMessage("Page not Found!");
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
            } else $JSONResponse->setErrorMessage("Page not defined!");
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
                    $JSONResponse->addNamedErrorMessage('username', "Username/Email not found!");
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
            $user = User::FromArray(array_merge(
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
                $JSONResponse->setErrorMessage('An error occurred while creating the user. No changes saved.');
            break;
        case 'addJob':
            $data['category'] = \App\Models\LABEL_JobCategory::findByID($data['category']);
            $job = \App\Models\Job::FromArray($data);

            if ($job->save()) {
                $JSONResponse->setSuccessMessage("Job successfully posted!");
                $JSONResponse->addUnique('xid', $job->id);
            } else
                $JSONResponse->setErrorMessage('An error occurred while creating the user. No changes saved.');
            break;
        case 'applyCancelJob':
            if (!array_key_exists('id', $data))
                $JSONResponse->setErrorMessage('An error occurred!');
            else {
                if (array_key_exists('uid', $_SESSION)) {
//                    $JSONResponse->addUnique('reach', true);
                    $user = $GLOBALS['user']->box();
                    $job = \App\Models\Job::findByID($data['id'])->box();

                    if (\App\Models\User_Job::relationshipExists($user, $job)) {
                        if (\App\Models\User_Job::severRelationship($user, $job))
                            $JSONResponse->setSuccessMessage('Job application cancelled successfully!');
                    } else {
                        if (\App\Models\User_Job::newRelationship($user, $job))
                            $JSONResponse->setSuccessMessage('Job applied for successfully!');
                    }
//                if (\App\Models\User_Job::relationshipExists($user->box(), $job->box()))
//                    $JSONResponse->addUnique('result', (\App\Models\User_Job::relationshipExists($user->box(), $job->box())));
//                if ($job->box()->appliedFor($user->id)) {
//                    $JSONResponse->setErrorMessage('Cancel');
//                } else {
//                    $user->ownJobList[] = $job;
//                    if ($user->box()->save())
//                        $JSONResponse->setSuccessMessage('Job applied for successfully!');
//                }

//                    $JSONResponse->addUnique('test[user]', $user->__toJSON());
//                    $JSONResponse->addUnique('test[job]', $job->box()->__toJSON());
//                    $JSONResponse->addUnique('test[job->appliedFor]', $job->box()->appliedFor($user->id));
                }
            }
            break;
        default:
            $JSONResponse->setErrorMessage('404.1 An error occurred!');
            $JSONResponse->setStatus(404);
    }
}

<<<<<<< HEAD
// $req_dump = print_r($_REQUEST, TRUE);
// $fp = fopen('request.log', 'a');
// fwrite($fp, $req_dump);
// fclose($fp);//
// $req_dump = print_r($JSONResponse->getResponse(), TRUE);
// $fp = fopen('response.log', 'a');
// fwrite($fp, $req_dump);
// fclose($fp);
=======
//$req_dump = print_r($_REQUEST, TRUE);
//$fp = fopen('request.log', 'a');
//fwrite($fp, $req_dump);
//fclose($fp);
//$req_dump = print_r($JSONResponse->getResponse(), TRUE);
//$fp = fopen('response.log', 'a');
//fwrite($fp, $req_dump);
//fclose($fp);
//
>>>>>>> ccf2bcfefd6d10c8562f32f284687310b2a9d65c
$JSONResponse->respond();
