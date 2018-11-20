<?php namespace Namacode\ArmyKnife;
class Handlers {
    public static function load()
    {
        if (function_exists('exception_handler'))
            set_exception_handler('exception_handler');
        if (function_exists('error_handler'))
            set_error_handler('error_handler');
    }
}