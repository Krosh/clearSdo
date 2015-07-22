<?php

error_reporting(E_ALL & ~E_NOTICE);

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
//$config=dirname(__FILE__).'/protected/config/main.php';

require_once($yii);

// remove the following line when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);


// if($_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
if($_SERVER['REMOTE_ADDR'] == "::1" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1"){
    $config=dirname(__FILE__).'/protected/config/local.php';
} else
{
    $config=dirname(__FILE__).'/protected/config/main.php';
}

Yii::createWebApplication($config)->run();
