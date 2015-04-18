<?php

// TODO:: не знаю, правильно ли запихивать сюда эти константы, может стоит их убрать в отдельный файл

define("MATERIAL_FILE", 1);
define("MATERIAL_LINK", 2);
define("MATERIAL_TORRENT", 3);
define("MATERIAL_TITLE", 4);
define("MATERIAL_TEST", 5);

define("ROLE_GUEST",-1);
define("ROLE_STUDENT",0);
define("ROLE_TEACHER",1);
define("ROLE_ADMIN",2);
$path = "/pub/home/mvtom/stml2/protected/plugins";
$result = array();

if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file == "." || $file == "..") continue;
        if (is_dir($path.DIRECTORY_SEPARATOR.$file))
        {
            array_push($result,"application.plugins.".$file.".*");
        }
    }
    closedir($handle);
}

return array(
    'name'=>'SDO Stimul v 2.0',
    'sourceLanguage'=>'ru',
    'language'=>'ru',
    //'defaultController'=>'site',
    'params' => array(
        'images' => array("png","bmp","jpg","jpeg","gif"),
        'timezone' => "Asia/Omsk",
        'avatarStatuses' => array('Проходит модерацию', 'Одобрен модератором', 'Заблокирован модератором'),
        'roles' => array (ROLE_ADMIN => 'Администратор', ROLE_STUDENT => 'Студент', ROLE_TEACHER => 'Преподаватель'),
    ),

    'import'=>array_merge(array(
        'application.models.*',
        'application.components.*',
        'application.classes.*',
        'application.helpers.*',
        'application.plugins.*',
        'ext.xupload.*',
    ),$result),

    'aliases' => array(
        //If you manually installed it
        'xupload' => 'ext.xupload'
    ),
    'components'=>array(

		'db'=>array(
            'class'=>'system.db.CDbConnection',
            'connectionString'=>'mysql:host=db36.valuehost.ru;dbname=mvtom_stml2',
            'username'=>'mvtom_stml2',
            'password'=>'perchik',
            'charset'=>'utf8'
        ),
        'authManager' => array(
            // Будем использовать свой менеджер авторизации
            'class' => 'PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => array('guest'),
        ),
        'user'=>array(
            'class' => 'WebUser',
            // …
        ),
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
                'gii'=>'gii',
                '<action:\w+>' => 'site/<action>',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'site/<action:\w>'=>'index.php/site/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			  	'<controller:\w+>/<action:[0-9a-zA-Z_\-]+>' => '<controller>/<action>',
           		'<controller:\w+>' => '<controller>/index',
     //           		'index.php/site' => 'site/index',
			),
		),
	),
);