<?php
declare(strict_types=1);


namespace DcrPHP\Log;


/**
 * 系统的日志
 * Class SystemLogger
 * @package DcrPHP\Log
 */
class SystemLogger
{

    /**
     * 日志信息
     * @var
     */
    private $logInfo;
    private $clsLog;

    public function __construct($configPath)
    {
        //初始化
        $clsLog = new Log($configPath);
        $clsLog->init();
        $this->clsLog = $clsLog;
    }

    public function addHandler($handlerName)
    {
        $this->clsLog->addHandler($handlerName);
    }

    /**
     * @param mixed $logInfo
     * @throws \Exception
     */
    public function setLogInfo($logInfo)
    {
        $this->logInfo = $logInfo;
        if (!$this->checkLogInfoFormat()) {
            throw new \Exception('日志信息有问题，请查看wiki下的规范要求');
        }
    }

    /**
     * 检测日志格式
     * @return bool
     */
    private function checkLogInfoFormat()
    {
        return isset($this->logInfo['ack']) && isset($this->logInfo['level']) && isset($this->logInfo['add_time']) && isset($this->logInfo['message']) && isset($this->logInfo['source']);
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
        return call_user_func_array([$this->clsLog, $method], array('日志',$this->logInfo));
    }
}