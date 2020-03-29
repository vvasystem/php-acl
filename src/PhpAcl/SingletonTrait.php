<?php

namespace PhpAcl;

trait SingletonTrait
{

    private static $instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    protected function __construct()
    {
    }

    /**
     * @codeCoverageIgnore
     */
    protected function __clone()
    {
    }

    /**
     * @codeCoverageIgnore
     */
    protected function __wakeup()
    {
    }

}