<?php
declare(strict_types=1);


namespace DcrPHP\Log\Handler;

use DcrPHP\Log\Concerns\Handler;
use Monolog\Handler\BrowserConsoleHandler;

class Browser extends Handler
{

    public function checkConfig()
    {
        // TODO: Implement checkConfig() method.
        $config = $this->getConfig();
        return isset($config) && isset($config['path']);
    }
    public function init()
    {
        if( ! $this->checkConfig() )
        {
            throw new \Exception('配置不存在或配置有问题');
        }
        $config = $this->getConfig();
        return new StreamHandler($config['path']);
    }
}