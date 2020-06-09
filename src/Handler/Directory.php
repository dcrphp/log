<?php
declare(strict_types=1);


namespace DcrPHP\Log\Handler;

use DcrPHP\Log\Concerns\Handler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\StreamHandler;

class Directory extends Handler
{

    public function checkConfig()
    {
        // TODO: Implement checkConfig() method.
        $config = $this->getConfig();
        return isset($config) && isset($config['path']);
    }

    public function init()
    {
        if (!$this->checkConfig()) {
            throw new \Exception('配置不存在或配置有问题');
        }
        $config = $this->getConfig();

        $path = $config['path'] . DIRECTORY_SEPARATOR;
        $prefix = $config['prefix'] ? $config['prefix'] : 'log';
        $general = $config['general'] ? $config['general'] : 'day';
        if ('day' == $general) {
            $path .= date('Y-m-d');
        }
        if ('month' == $general) {
            $path .= date('Y-m');
        }
        if ('minute' == $general) {
            $path .= date('Y-m-d-H-i');
        }
        if ('hour' == $general) {
            $path .= date('Y-m-d-H');
        }
        $path .= '.' . $prefix;

        return new StreamHandler($path);
    }
}