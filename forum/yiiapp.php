<?php
// Yii-приложение для форума
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
// change the following paths if necessary
$yii = dirname(__FILE__).'/../framework/yii.php';
$yii_config = dirname(__FILE__).'/../protected/config/local.php';
require_once($yii);
Yii::createWebApplication($yii_config);
// Переопределяем корневую директорию с "/server/www/forum" на "/server/www", иначе Yii не будет работать как нам надо
Yii::setPathOfAlias('webroot', Yii::getPathOfAlias('webroot').DS.'..');
// Делаем то же самое для папки assets
Yii::app()->assetManager->setBasePath(Yii::getPathOfAlias('webroot').DS.'assets');