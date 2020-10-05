<?php

declare(strict_types=1);

namespace DcrPHP\Log\Handler;

use DcrPHP\Log\Concerns\Handler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\GelfHandler;

class Graylog extends Handler
{

    public function checkConfig()
    {
        // TODO: Implement checkConfig() method.
        $config = $this->getConfig();
        return isset($config) && isset($config['host']) && isset($config['port']);
    }
    public function init()
    {
        if (! $this->checkConfig()) {
            throw new \Exception('配置不存在或配置有问题');
        }

        $config = $this->getConfig();
        $transport = new \Gelf\Transport\UdpTransport($config['host'], $config['port']);
        $publisher = new \Gelf\Publisher();
        $publisher->addTransport($transport);
//print_r($publisher);

        return new GelfHandler($publisher);
    }
}
