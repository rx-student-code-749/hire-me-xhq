<?php namespace Namacode\ArmyKnife\R;

use RedBeanPHP\R;

abstract class BeanCentricModel extends \RedBeanPHP\SimpleModel
{
    use \Namacode\ArmyKnife\Traits\R\Model;

    public function __construct($bean = null)
    {
        if (is_null($bean))
            $this->bean = R::dispense(self::tableName());
        elseif (is_int($bean))
            $this->bean = R::load(self::tableName(), $bean);
        else
            $this->bean = $bean;
    }
    public function __set($prop, $value)
    {
        if (property_exists(get_called_class(), $prop))
            $this->bean->${$prop}=$value;
    }
    public function __get($prop)
    {
        if (property_exists(get_called_class(), $prop))
            return $this->bean->${$prop} ?: null;
        return null;
    }
    public function __call($name, $arguments)
    {
        $tmp = substr($name, 0, 3);

        switch ($tmp){
            case 'get':
                $prop = \Namacode\ArmyKnife\extendString_fromCamelCase(substr($name, 4));
                if (property_exists(get_called_class(), $prop))
                    return $this->$prop;
        }
        return null;
    }

    public function save () : int{
        return R::store($this->bean);
    }
}
