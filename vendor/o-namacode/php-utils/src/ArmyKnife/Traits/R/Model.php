<?php
/**
 * Created by PhpStorm.
 * User: raphe
 * Date: 1/8/2018
 * Time: 2:18 AM
 */

namespace Namacode\ArmyKnife\Traits\R;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * Trait Model
 * @package Namacode\ArmyKnife\Traits\R
 * @method unbox
 */
trait Model
{
    public static $config = [
        'table_name' => null
    ];

    public static function tableName(): string
    {
        $tmp = get_called_class();
        $tmp = explode("\\", $tmp);

        return (array_key_exists('table_name', self::$config)) ?
            (is_null(self::$config['table_name']))
                ? strtolower(end($tmp))
                : self::$config['table_name']
            : strtolower(end($tmp));
//        return is_null(self::$config['table_name']) ? strtolower(end($tmp)) : self::$config['table_name'];
    }

    public static function foreignKey(): string
    {
        return self::tableName() . "_id";
    }

    public static function findByID($id, $snippet = null): OODBBean
    {
        return R::load(self::tableName(), $id, $snippet);
    }

    public function save(): int
    {
        return R::store($this->unbox());
    }
//    public function __call($name, $arguments)
//    {
//        var_dump($name);
//    }
}
