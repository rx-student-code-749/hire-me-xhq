<?php namespace Namacode\ArmyKnife\R;

use Namacode\ArmyKnife\R\Contracts\ModelInterface;
use Namacode\ArmyKnife\Traits\R\Model as Model_Trait;
use RedBeanPHP\R;
use RedBeanPHP\SimpleModel;

abstract class EnumAwareModel extends SimpleModel implements ModelInterface
{
    use Model_Trait;

    private static function safe_guard_config()
    {
        self::$config = [
            'table_name' => (array_key_exists('table_name', self::$config)) ? self::$config['table_name'] : null,
            'table_prefix' =>(array_key_exists('table_prefix', self::$config)) ?  self::$config['table_prefix'] : "ENUM_",
            'table_suffix' => (array_key_exists('table_suffix', self::$config)) ? self::$config['table_suffix'] : "",
            'save_with_prefix' => (array_key_exists('save_with_prefix', self::$config)) ? self::$config['save_with_prefix'] : false,
            'save_with_suffix' =>(array_key_exists('save_with_suffix', self::$config)) ?  self::$config['save_with_suffix'] : false,
        ];
    }

    public static function Get($name)
    {
        return R::enum(self::tableName() . ":" . $name);
    }

    /**
     * Table prefix and suffix filtering replaces all corresponding matching strings.
     * Take care not to include a defined suffix as part of the desired table name if you have filtering on.
     *
     * @return string
     */
    public static function tableName(): string
    {
        self::safe_guard_config();
        $tmp = explode("\\", get_called_class());
        $tableName = strtolower(end($tmp));

        if (array_key_exists('save_with_prefix', self::$config))
        if (!self::$config['save_with_prefix'])
            $tableName = str_ireplace(self::$config['table_prefix'], "", $tableName);
        if (array_key_exists('save_with_suffix', self::$config))
        if (!self::$config['save_with_suffix'])
            $tableName = str_ireplace(self::$config['table_suffix'], "", $tableName);
        return $tableName;
    }

    public static function List()
    {
        return R::enum(self::tableName());
    }
}
