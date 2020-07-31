# DcrPHP/Log日志类

## 1、安装
　　composer require dcrphp/log

## 2、案例
　　在example目录下  

## 3、说明
　　1、用户日志：用户的操作等,只支持mongodb,如果想用用户日志，请在config里配置好mongodb的配置  
　　2、系统日志：系统自动执行的计划任务、系统自动执行的日志等    
　　3、如果配置里有handler配置，则会添加handler，如果没有配置可以额外添加addHandler