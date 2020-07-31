<?php
declare(strict_types=1);

namespace DcrPHP\Log;

use DcrPHP\Config\Config;
use Monolog\Logger;

class Log
{
    private $config = array();
    private $handlerList = array();
    private $clsMonolog;

    /**
     * Log constructor.
     * @param string $configPath 配置文件
     * @throws \Exception
     */
    public function __construct($configPath = '')
    {
        if ($configPath) {
            $this->setConfigFile($configPath);
        }
    }

    /**
     * 人工配置
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * 配置文件
     * @param $configPath
     * @throws \Exception
     */
    public function setConfigFile($configPath)
    {
        $clsConfig = new Config($configPath);
        $clsConfig->setDriver('php');//解析php格式的
        $clsConfig->init();
        $this->config = current($clsConfig->get());
        if($this->config['handler'])
        {
            $this->addHandler($this->config['handler']);
        }
    }

    /**
     * 添加处理器
     * @param $handlerName
     * @throws \Exception
     */
    public function addHandler($handlerName)
    {
        $lh = new LogHandler();
        $lh->setConfig($this->config);
        $lh->setHandlerName($handlerName);
        $cls = $lh->init();
        $this->handlerList[] = $cls;
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

    /**
     * 初始化
     * @throws \Exception
     */
    public function init()
    {
        $this->checkConfig();
        $this->clsMonolog = new Logger($this->config['channel']);
        /*if (!$this->handlerList) {
            throw new \Exception('can not find handler');
        }*/

        foreach ($this->handlerList as $handler) {
            $this->clsMonolog->pushHandler($handler);
        }
    }

    /**
     * @method warning
     * @method info
     * @method debug
     * @method notice
     * @method critical
     * @method emergency
     * 调用日志核心类的方法
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->clsMonolog, $method], $args);
    }
}