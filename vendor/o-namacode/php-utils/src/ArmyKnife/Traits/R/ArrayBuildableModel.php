<?php namespace Namacode\ArmyKnife\Traits\R;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * Trait ArrayBuildableModel
 * @package Namacode\ArmyKnife\Traits\R
 * @method  set ($v, $y)
 * @method static string tableName ()
 *
 */
trait ArrayBuildableModel {

    /**
     * ArrayBuildableModel constructor.
     * @param OODBBean|array|null $bean
     */
    public function __construct($bean = null)
    {
        if (is_array($bean))
            foreach ($bean as $x => $y)
                $this->set($x, $y);

        /** @noinspection PhpUndefinedClassInspection */
        parent::__construct($bean);
    }


    public static function FromArray(array $arr) : self
    {
        $buildableModel = self::_build();

        foreach ($arr as $x => $y)
            $buildableModel->set($x, $y);

        return $buildableModel;
    }
}
