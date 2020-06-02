<?php
require_once("../vendor/autoload.php");

use DcrPHP\Log\Log;

ini_set('display_errors', 'on');

//加载配置
$clsLog = new Log();
//$clsLog->setConfig(array('file' => array('path' => 'log')));
$clsLog->setConfigFile('config.php');
$clsLog->addHandle('graylog'); //记录在file中
//$clsLog->addHandle('mongodb'); //记录在mongodb中
$clsLog->init();
//写日志
$clsLog->warning('Information message', ['user' => get_current_user()]);