<?php
declare(strict_types=1);

namespace DcrPHP\Log;

use DcrPHP\Config\Config;
use Monolog\Handler\AbstractSyslogHandler;
use Monolog\Logger;

class Log
{
    private $config = array();
    private $handleList = array();
    private $clsMonolog;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function setConfigFile($config)
    {
        $clsConfig = new Config();
        $clsConfig->addFile($config);
        $clsConfig->setDriver('php');//解析php格式的
        $clsConfig->init();
        $this->config = current($clsConfig->get());
    }

    public function addHandler($handleName)
    {
        $lh = new LogHandler();
        $lh->setConfig($this->config);
        $lh->setName($handleName);
        $cls = $lh->init();
        $this->handleList[] = $cls;
    }

    /**
     * 检查配置
     * @throws \Exception
     */
    public function checkConfig()
    {
        $config = $this->config;
        if(empty($config['channel']))
        {
            throw new \Exception('can not find the channel');
        }
    }

    public function init()
    {
        $this->checkConfig();
        $this->clsMonolog = new Logger($this->config['channel']);
        if (!$this->handleList) {
            throw new \Exception('can not find handle');
        }

        foreach ($this->handleList as $handle) {
            $this->clsMonolog->pushHandler($handle);
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->clsMonolog, $method], $args);
    }
}