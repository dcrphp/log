<?php

declare(strict_types=1);

namespace DcrPHP\Log;

use DcrPHP\Config\Config;

/**
 * 用户操作日志
 * Class UserLogger
 * @package DcrPHP\Log
 */
class UserLogger
{
    /**
     * 日志信息
     * @var
     */
    private $logInfo;
/**
     * 集合
     * @var
     */
    private $collection;
    private $config;
/**
     * mongodb实例
     * @var
     */
    private $mongodbInstance;
/**
     * @param mixed $logInfo
     * @throws \Exception
     */
    public function setLogInfo($logInfo)
    {
        $this->logInfo = $logInfo;
        if (!$this->checkLogInfoFormat()) {
            throw new \Exception('日志必填的信息有问题，请查看wiki下的规范要求');
        }
    }

    public function __construct($collection, $configPath = '')
    {
        $this->initConfig($configPath);
        $this->collection = $collection;
        $this->initMongodb();
    }

    private function initMongodb()
    {
        if (!class_exists('MongoDB\Driver\Manager')) {
            throw new \Exception('php请开启mongodb组件');
        }
        $config = $this->config['mongodb'];
        $auth = $config['username'] && $config['username'] ? "{$config['username']}:{$config['username']}@" : "";
        $uri = "mongodb://{$auth}{$config['host']}:{$config['port']}";
//echo $uri;

        //连接mongodb
        $this->mongodbInstance = new \MongoDB\Driver\Manager($uri);
    }

    /**
     * 检测日志格式
     * @return bool
     */
    private function checkLogInfoFormat()
    {
        return isset($this->logInfo['add_time']) && isset($this->logInfo['add_user']) && isset($this->logInfo['type']) && isset($this->logInfo['message']) && isset($this->logInfo['source']);
    }

    /**
     * 检查配置，检测mongodb有没有配置
     */
    private function checkConfigFormat()
    {
        return isset($this->config['mongodb']);
    }

    private function initConfig($configPath)
    {
        $clsConfig = new Config();
        $clsConfig->addFile($configPath);
        $clsConfig->setDriver('php');
//解析php格式的
        $clsConfig->init();
        $this->config = current($clsConfig->get());
        if (!$this->checkConfigFormat()) {
            throw new \Exception('请配置好mongodb');
        }
    }

    /**
     * 添加日志
     * @param $collectionName
     * @param $logInfo
     * @param string $configPath
     * @throws \Exception
     */
    public function save()
    {
        //开始存
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->insert($this->logInfo);
        $config = $this->config['mongodb'];
//var_dump(  );
        return $this->mongodbInstance->executeBulkWrite("{$config['database']}.{$this->collection}", $bulk);
    }

    /**
     * @param $page 第几页
     * @param $list_num 一页多少条
     * @param array $where 条件
     * @param int[] $order 排序
     * @param array $field 要的字段
     */
    public function getList($page, $list_num, $where = array(), $order = array('_id' => -1), $field = array())
    {
        if (!$where) {
            $where = array();
        }
        $filter = $where;
        $start = ($page - 1) * $list_num;
        $options = array('limit' => $list_num, 'skip' => $start, 'sort' => $order);
        $config = $this->config['mongodb'];
        $query = new \MongoDB\Driver\Query($filter, $options);
        $list = $this->mongodbInstance->executeQuery("{$config['database']}.{$this->collection}", $query);
        $arr = array();
        foreach ($list as $document) {
            array_push($arr, (array)$document);
        }
        return $arr;
    }
}
