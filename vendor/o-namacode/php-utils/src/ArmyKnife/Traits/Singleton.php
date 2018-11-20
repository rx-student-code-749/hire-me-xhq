<?php namespace Namacode\ArmyKnife\Traits;
trait Singleton
{
    private static $_instance;

    /**
     * Singleton constructor.
     * @param array|null $cfg
     */
    public function __construct(array $cfg = null) {}

    /**
     * @param array $config
     * @return Singleton
     */
    public static function GetInstance(array $config = null) : self
    {
        if (!self::$_instance)
            self::$_instance = new self($config);
        return self::$_instance;
    }
}