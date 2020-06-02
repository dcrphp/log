<?php
return array(
    'channel' => 'log',
    //频道名 一般定义为系统名
    'file' => array('path' => 'log.log'),
    //directory为日志生成在path目录下， general为day则按天 hour按时 month按月 minute按分，prefix为日志文件后缀默认为log
    'directory' => array('path' => 'log', 'prefix' => 'php', 'general' => 'hour'),
    'mongodb' => array(
        'host' => 'ip',
        'port' => 'port',
        'database' => 'database',
        'collection' => 'collection'
    ),
    'redis' =>
        array(
            'host' => 'host',
            'port' => 'port',
            'password' => 'password',
            'key'=> 'log',
        ),
    //要用graylog,则要引入:graylog2/gelf-php.
    //这个ip和port请在graylog中的 System/inputs->inputs 新建一个gelf udp就OK了，记得端口配置在下面
    'graylog'=> array('host'=>'10.10.30.217','port'=>'12201'),
);