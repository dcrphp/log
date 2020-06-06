<?php
require_once("../vendor/autoload.php");

use DcrPHP\Log\Log;

ini_set('display_errors', 'on');

//加载配置
$clsLog = new Log('config.php'); //默认是文件日志
//$clsLog->setConfig(array('file' => array('path' => 'log')));
//$clsLog->setConfigFile('config.php');
//$clsLog->addHandler('graylog'); //记录在file中
//$clsLog->addHandler('browser'); //记录在file中
//$clsLog->addHandle('mongodb'); //记录在mongodb中
//$clsLog->init(); //如果后面有添加配置请init
//写日志
$clsLog->warning('my message', ['user' => get_current_user()]);
$clsLog->info('my message', ['user' => get_current_user()]);
$clsLog->debug('my message', ['user' => get_current_user()]);
$clsLog->notice('my message', ['user' => get_current_user()]);
$clsLog->critical('my message', ['user' => get_current_user()]);
$clsLog->emergency('my message', ['user' => get_current_user()]);