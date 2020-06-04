<?php
declare(strict_types=1);

namespace DcrPHP\Log;

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\RedisHandler;
use Monolog\Handler\StreamHandler;

class LogHandler
{
    private $config;
    private $handleName;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function setName($handelName)
    {
        $this->handleName = $handelName;
    }

    public function init()
    {
        $config = $this->config[$this->handleName];
        //查看配置
        if (!$config) {
            throw new \Exception('config can not find:' . $this->handleName);
            return;
        }
        $handle = null;
        try {
            $handle = $this->getHandle($config);
        } catch (\Exception $e) {
        }
        return $handle;
    }

    /**
     * 通过配置来获取handle
     * @param $config
     */
    public function getHandle($config)
    {
        switch ($this->handleName) {
            case 'browser':
                $handle = new BrowserConsoleHandler();
                break;
            case 'graylog':
                $transport = new \Gelf\Transport\UdpTransport($config['host']);
                $publisher = new \Gelf\Publisher();
                $publisher->addTransport($transport);
                //print_r($publisher);

                $handle = new GelfHandler($publisher);
                break;
            case 'file':
                $handle = new StreamHandler($config['path']);
                break;
            case 'mongodb':
                $client = new \MongoDB\Driver\Manager("mongodb://{$config['host']}:{$config['port']}");
                $handle = new MongoDBHandler($client, $config['database'], $config['collection']);
                break;
            case 'redis':
                $client = new \Redis();
                $client->connect($config['host'], $config['port']);
                if ($config['password']) {
                    $client->auth($config['password']);
                }
                $handle = new RedisHandler($client, $config['key']);
                break;
            case 'directory':
                $path = $config['path'] . DIRECTORY_SEPARATOR;
                $prefix = $config['prefix'] ? $config['prefix'] : 'log';
                if ('day' == $config['general']) {
                    $path .= date('Y-m-d');
                }
                if ('month' == $config['general']) {
                    $path .= date('Y-m');
                }
                if ('minute' == $config['general']) {
                    $path .= date('Y-m-d-H-i');
                }
                if ('hour' == $config['general']) {
                    $path .= date('Y-m-d-H');
                }
                $path .= '.' . $prefix;
                $handle = new StreamHandler($path);
                break;
            default:
                throw new \Exception('invalid handle:' . $this->handleName);
                break;
        }
        return $handle;
    }
}