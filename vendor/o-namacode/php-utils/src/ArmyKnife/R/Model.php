<?php namespace Namacode\ArmyKnife\R;

use Namacode\ArmyKnife\R\Contracts\ModelInterface;
use Namacode\ArmyKnife\Traits\R\Model as Model_Trait;
use Namacode\ArmyKnife\Traits\R\SelfCreatingModel;
use RedBeanPHP\SimpleModel;

abstract class Model extends SimpleModel implements ModelInterface {
    use Model_Trait;
    use SelfCreatingModel;
}