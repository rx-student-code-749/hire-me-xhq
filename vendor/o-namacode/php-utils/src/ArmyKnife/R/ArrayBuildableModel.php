<<<<<<< HEAD
<?php namespace Namacode\ArmyKnife\R;

use Namacode\ArmyKnife\R\Contracts\ModelInterface;
use Namacode\ArmyKnife\Traits\R\Model as Model_Trait;
use Namacode\ArmyKnife\Traits\R\SelfCreatingModel;
use RedBeanPHP\SimpleModel;

abstract class ArrayBuildableModel extends Model
{
    use \Namacode\ArmyKnife\Traits\R\ArrayBuildableModel;

    abstract public static function _build ($bean = null);
}
=======
<?php namespace Namacode\ArmyKnife\R;

use Namacode\ArmyKnife\R\Contracts\ModelInterface;
use Namacode\ArmyKnife\Traits\R\Model as Model_Trait;
use Namacode\ArmyKnife\Traits\R\SelfCreatingModel;
use RedBeanPHP\SimpleModel;

abstract class ArrayBuildableModel extends Model
{
    use \Namacode\ArmyKnife\Traits\R\ArrayBuildableModel;

    abstract public static function _build ($bean = null);
}
>>>>>>> ccf2bcfefd6d10c8562f32f284687310b2a9d65c
