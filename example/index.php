<?php
require_once("../vendor/autoload.php");

use DcrPHP\Log\UserLogger;
use DcrPHP\Log\SystemLogger;

ini_set('display_errors', 'on');

//系统日志
$clsSystemLogger = new SystemLogger('log.php'); //配置文件
//$clsSystemLogger->addHandler('mongodb');//除了配置里的driver 还要额外记，请在这里加
$clsSystemLogger->setLogInfo(
    array(
        'ack' => 1, //1或0 这是成功还是失败
        'level' => 'info', //warning info debug notice critical emergency
        'add_time' => '2020-06-05 12:12:12',
        'message' => '日志主体',
        'source' => '日志来源:dcrphp',
    )
);
$clsSystemLogger->notice();
exit;

//用户日志
$clsUserLogger = new UserLogger('log_user', 'log.php'); //集合名,配置路径 格式看log.php案例 用户日志要配置好mongodb
//$clsUserLogger = new UserLogger('log_user','config.php');//使用config.php的配置
$clsUserLogger->setLogInfo(
    array(
        'add_time' => '2020-06-05 12:12:12',
        'add_user' => '张三',
        'type' => '添加',
        'message' => '日志主体',
        'source' => '日志来源:dcrphp',
    )
);//格式请看wiki:用户日志格式
$clsUserLogger->save();

$logList = $clsUserLogger->getList(1, 100);
print_r($logList);

//系统日志
/*$clsSystemLogger = new SystemLogger();
$clsSystemLogger->setLogInfo(array());
$clsSystemLogger->notice();*/
//$clsSystemLogger->warning('my message', ['user' => get_current_user()]);
//$clsSystemLogger->info('my message', ['user' => get_current_user()]);
//$clsSystemLogger->debug('my message', ['user' => get_current_user()]);
//$clsSystemLogger->notice('my message', ['user' => get_current_user()]);
//$clsSystemLogger->critical('my message', ['user' => get_current_user()]);
//$clsSystemLogger->emergency('my message', ['user' => get_current_user()]);