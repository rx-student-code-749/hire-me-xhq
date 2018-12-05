<?php namespace Namacode\ArmyKnife\Traits\R;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

trait MetaModel {
    public function __toJSON()
    {
        $o = [];
        foreach (get_object_vars($this) as $k)
            $o[$k] = $this->{$k};
        return json_encode($o);
    }
}
