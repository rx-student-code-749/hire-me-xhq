<?php namespace Namacode\ArmyKnife\Traits\R;

use RedBeanPHP\R;

trait TimeAwareModel {
    public function dispense () {
        $this->date_created = R::isoDateTime();
    }
    public function update () {
        $this->date_modified = R::isoDateTime();
    }
}
