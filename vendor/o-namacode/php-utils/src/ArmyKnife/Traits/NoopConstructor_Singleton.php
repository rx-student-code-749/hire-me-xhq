<?php namespace Namacode\ArmyKnife\Traits;
trait NoopConstructor_Singleton
{
    /**
     * NoopConstructor_Singleton constructor.
     * @param null $config
     */
    public function __construct($config = null)
    {
        parent::__construct(null);
    }
}