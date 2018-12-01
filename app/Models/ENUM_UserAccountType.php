<?php
namespace App\Models;

use Namacode\ArmyKnife\R\EnumAwareModel;

class ENUM_UserAccountType extends EnumAwareModel {
    const STANDARD = 'standard';
    const ADMIN = 'admin';

    public static function is($id, $str)
    {
        $tmp = self::findByID($id);
        $etmp = self::Get($str);
        return $tmp->equals($etmp);
    }
}
class_alias(ENUM_UserAccountType::class, str_replace("ENUM_", "",ENUM_UserAccountType::class));
