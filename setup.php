<?php
require_once __DIR__ . "/app/app.core.php";

error_reporting(E_ALL);
$u = \App\Models\User::newFromArray([
    'first_name' => "Administrator",
    'last_name' => "One",
    'username' => "admin",
    'email' => "admin@hireme.com",
    'password' => "password123",
    'account_type' => \App\Models\ENUM_UserAccountType::Get(\App\Models\ENUM_UserAccountType::ADMIN)
]);

//echo "<pre>", var_export($u);
$u->save();

