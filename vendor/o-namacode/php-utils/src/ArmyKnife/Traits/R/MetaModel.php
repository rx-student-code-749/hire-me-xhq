<<<<<<< HEAD
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
=======
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
>>>>>>> ccf2bcfefd6d10c8562f32f284687310b2a9d65c
