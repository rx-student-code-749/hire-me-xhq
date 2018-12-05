<<<<<<< HEAD
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
=======
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
>>>>>>> ccf2bcfefd6d10c8562f32f284687310b2a9d65c
