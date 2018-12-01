<?php namespace App\Models;

use Namacode\ArmyKnife\Traits\R\TimeAwareModel;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * @property mixed id
 */
class Job extends \Namacode\ArmyKnife\R\TimeAwareModel
{
    public $title, $description, $category, $company_name, $company_location, $posted;

    public function __construct(OODBBean $bean = null)
    {
        if (!is_null($bean))
            $this->bean = $bean;
    }

    public function dispense()
    {
        $this->bean->posted = $this->posted = R::isoDateTime();
        parent::dispense();
    }
    public static function newFromArray(array $arr) : Job
    {
        /** @var Job $job */
        $job = new self(R::dispense(self::tableName()));

        foreach ($arr as $x => $y)
                if (property_exists($job, $x))
                    $job->bean->$x = $job->$x = $y;

        return $job;
    }
    public function __toString()
    {
        $o = [];
        $tmp = get_object_vars($this);
        foreach ($tmp as $k)
            $o[$k] = $this->{$k};
        return json_encode($o);
    }
    public static function GetAll () {
        return R::findAll(self::tableName());
    }

    public function getCategory()
    {
        return LABEL_JobCategory::findByID($this->bean->category_id);
    }

    public function appliedFor (int $uid) {
        return false;
    }
}
