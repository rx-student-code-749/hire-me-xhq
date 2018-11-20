<?php
namespace Namacode\ArmyKnife\Traits\R;
use RedBeanPHP\R;

trait SelfCreatingModel {
    public static function New() {
//        if (isset(func_get_args()[0]))
//        if (is_array(func_get_args()[0])) {
//
//        }

        return R::dispense(self::tableName());
    }
}