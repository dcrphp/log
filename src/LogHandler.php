<?php
declare(strict_types=1);

namespace DcrPHP\Log;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\StreamHandler;

class LogHandler
{
    private $config;
    private $handlerName;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function setHandlerName($handlerName)
    {
        $this->handlerName = $handlerName;
    }

    /**
     * 初始化好handler返回给使用者
     * @return MongoDBHandler|BrowserConsoleHandler|GelfHandler|RedisHandler|StreamHandler|void|null
     * @throws \Exception
     */
    public function init()
    {
        $config = $this->config[$this->handlerName];
        //查看配置
        if (!$config) {
            throw new \Exception('config can not find:' . $this->handlerName);
            return;
        }
        $handler = null;
        try {
            $handler = $this->getHandler($config);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $handler;
    }

    /**
     * 通过配置来获取handle
     * @param $config
     */
    public function getHandler($config)
    {
        //转给handler去处理
        $handlerClass = "\\DcrPHP\\Log\\Handler\\" . ucfirst($this->handlerName);
        $handler = new $handlerClass;
        $handler->setConfig($config);
        $clsHandler = $handler->init();
        if( ! $clsHandler instanceof AbstractProcessingHandler)
        {
            throw new \Exception('没有可用handler');
        }
        return $clsHandler;
    }
}