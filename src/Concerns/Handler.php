<?php

namespace DcrPHP\Log\Concerns;

abstract class Handler
{
    protected $config;

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
    abstract public function checkConfig();
    abstract public function init();
}
