<?php namespace Namacode\ArmyKnife\R;

use Namacode\ArmyKnife\R\Contracts\ModelInterface;
use Namacode\ArmyKnife\Traits\R\SelfCreatingModel;
use Namacode\ArmyKnife\Traits\R\TimeAwareModel as TimeAwareModel_Trait;
use RedBeanPHP\SimpleModel;

/**
 * @property string date_modified
 * @property string date_created
 */
abstract class TimeAwareModel extends SimpleModel implements ModelInterface {
    use TimeAwareModel_Trait;
    use SelfCreatingModel;
}