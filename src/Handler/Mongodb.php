<?php
declare(strict_types=1);


namespace DcrPHP\Log\Handler;

use DcrPHP\Log\Concerns\Handler;
use Monolog\Handler\BrowserConsoleHandler;

class Mongodb extends Handler
{

    public function checkConfig()
    {
        // TODO: Implement checkConfig() method.
        $config = $this->getConfig();
        return isset($config) && isset($config['host']) && isset($config['port']) && isset($config['database']) && isset($config['collection']);
    }
    public function init()
    {
        if( ! $this->checkConfig() )
        {
            throw new \Exception('配置不存在或配置有问题');
        }
        $config = $this->getConfig();

        $client = new \MongoDB\Driver\Manager("mongodb://{$config['host']}:{$config['port']}");
        return new MongoDBHandler($client, $config['database'], $config['collection']);
    }
}