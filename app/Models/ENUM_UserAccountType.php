<?php
namespace App\Models;

use Namacode\ArmyKnife\R\EnumAwareModel;

class ENUM_UserAccountType extends EnumAwareModel {
    const STANDARD = 'standard';
    const ADMIN = 'admin';
}
class_alias(ENUM_UserAccountType::class, str_replace("ENUM_", "",ENUM_UserAccountType::class));
