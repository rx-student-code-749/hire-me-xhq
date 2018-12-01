<?php
require_once __DIR__ . "/app/app.core.php";

error_reporting(E_ALL);

\RedBeanPHP\R::fancyDebug(true);

$u = \App\Models\User::newFromArray([
    'first_name' => "Administrator",
    'last_name' => "One",
    'email' => "admin@hireme.com",
    'password' => "password123",
    'account_type' => \App\Models\ENUM_UserAccountType::Get(\App\Models\ENUM_UserAccountType::ADMIN)
]);

//echo "<pre>", var_export($u);
$u->save();

\RedBeanPHP\R::storeAll(\RedBeanPHP\R::dispenseLabels(\App\Models\LABEL_JobCategory::tableName(), [
    "Information Technology",
    "Cosmetology",
    "Telemarketing"
]));
