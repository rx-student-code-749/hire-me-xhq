<?php namespace App\Models;

use Namacode\ArmyKnife\R\Model;
use Namacode\ArmyKnife\Traits\R\ArrayBuildableModel;
use Namacode\ArmyKnife\Traits\R\TimeAwareModel;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * @property mixed id
 */
class Job extends Model
{
    use ArrayBuildableModel;
    use TimeAwareModel;

    public static function _build($bean = null)
    {
        return new self(R::dispense(self::tableName()));
    }

    public $title, $description, $category, $company_name, $company_location, $posted;

    public function getCategory()
    {
        return LABEL_JobCategory::findByID($this->bean->category_id);
    }

    public function appliedFor (int $uid) {
        return false;
    }

    public static function GetAll ($sql = "", $binding = []) {
        return R::findAll(self::tableName(), $sql, $binding);
    }
}
