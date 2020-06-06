<?php
declare(strict_types=1);

namespace DcrPHP\Log;

use DcrPHP\Config\Config;
use Monolog\Handler\AbstractSyslogHandler;
use Monolog\Logger;
use mysql_xdevapi\Warning;

class Log
{
    private $config = array();
    private $handlerList = array();
    private $clsMonolog;

    /**
     * Log constructor.
     * @param $configPath 配置文件
     * @param string[] $handlerNameList 日志处理handler 默认是文件
     * @throws \Exception
     */
    public function __construct( $configPath, $handlerNameList = array('file') )
    {
        $this->setConfigFile($configPath);
        foreach($handlerNameList as $handlerName)
        {
            $this->addHandler($handlerName);
        }
        $this->init();
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
     * @param $config
     * @throws \Exception
     */
    public function setConfigFile($config)
    {
        $clsConfig = new Config();
        $clsConfig->addFile($config);
        $clsConfig->setDriver('php');//解析php格式的
        $clsConfig->init();
        $this->config = current($clsConfig->get());
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
        $lh->setName($handlerName);
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
        if (!$this->handlerList) {
            throw new \Exception('can not find handle');
        }

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