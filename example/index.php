<?php
require_once("../vendor/autoload.php");

use DcrPHP\Log\UserLogger;
use DcrPHP\Log\SystemLogger;

ini_set('display_errors', 'on');

//系统日志
$clsSystemLogger = new SystemLogger('log.php'); //配置文件 如果不用配置文件 就用下面的setConfig
//$clsSystemLogger->addHandler('graylog');//添加额外的处理
//$clsSystemLogger->setConfig(array()); //格式参考log.php
$clsSystemLogger->setTitle('标题内容-' . time());
$clsSystemLogger->setLogInfo(
    array(
        'ack' => 0, //1或0 这是成功还是失败
        'level' => 'critical', //warning info debug notice critical emergency
        'add_time' => date('Y-m-d H:i:s'),
        'message' => 1,
        'source' => 'dcrphp-error',
        'line'=> 2,
        'file_name'=> 3,
    )
);
$clsSystemLogger->notice();
echo '系统日志记录完成，请查看log/log.php';
exit;

//用户日志 只用mongodb
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