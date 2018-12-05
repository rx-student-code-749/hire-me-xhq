<?php
require_once __DIR__ . "/app/app.core.php";

error_reporting(E_ALL);

\RedBeanPHP\R::fancyDebug(true);
\RedBeanPHP\R::exec('SET FOREIGN_KEY_CHECKS = 0;');

\App\Models\User::FromArray([
    'first_name' => "Administrator",
    'last_name' => "One",
    'email' => "admin@hireme.com",
    'password' => "password123",
    'account_type' => \App\Models\ENUM_UserAccountType::Get(\App\Models\ENUM_UserAccountType::ADMIN)
])->save();

\RedBeanPHP\R::wipe(\App\Models\LABEL_JobCategory::tableName());
\RedBeanPHP\R::storeAll(\RedBeanPHP\R::dispenseLabels(\App\Models\LABEL_JobCategory::tableName(), [
    "Information Technology",
    "Cosmetology",
    "Telemarketing",
    "Education",
    "Entertainment",
    "Photography",
    "Computer Science",
    "Art",
]));

\RedBeanPHP\R::exec('SET FOREIGN_KEY_CHECKS = 1;');

echo "<a href='index.php'>Home</a>";