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
    public function __construct(OODBBean $bean = null)
    {
        if (!is_null($bean))
            $this->bean = $bean;
    }

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

    public function set (string $field_name, $value) : self
    {
        if (property_exists($this, $field_name))
            $this->bean->$field_name = $this->$field_name = $value;
        return $this;
    }

    public static function GetAll () {
        return R::findAll(self::tableName());
    }

}
