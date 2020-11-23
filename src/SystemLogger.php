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
/**
     * 日志实例
     * @var Log
     */
    private $clsLog;
/**
     * @var 日志标题
     */
    private $title;
/**
     * @return 日志标题
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function __construct($configPath = '')
    {
        //初始化
        try {
            $clsLog = new Log($configPath);
//$clsLog->init();
            $this->clsLog = $clsLog;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function setConfig($config)
    {
        $this->clsLog->setConfig($config);
    }

    /**
     * 给日志添加handler
     * @param $handlerName
     * @throws \Exception
     */
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
        $checkResult = $this->checkLogInfoFormat();
        if (!$checkResult['ack']) {
            throw new \Exception('日志信息有问题,以下字段缺失:' . $checkResult['msg']);
        }
    }

    /**
     * 检测日志格式
     * @return array
     */
    private function checkLogInfoFormat()
    {
        $result = 1;
        $loss = '';
        $checkList = array('ack', 'level', 'add_time', 'message', 'source');
        foreach ($checkList as $checkStr) {
            if (!isset($this->logInfo[$checkStr])) {
                $result = 0;
                $loss .= $checkStr . ',';
            }
        }

        return array('ack' => $result, 'msg' => $loss);
    }

    /**
     * 调用日志核心类的方法
     * @method warning
     * @method info
     * @method debug
     * @method notice
     * @method critical
     * @method emergency
     * @param $method
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        $this->clsLog->init();
        return call_user_func_array([$this->clsLog, $method], array($this->getTitle() ?? '日志', $this->logInfo));
    }
}
