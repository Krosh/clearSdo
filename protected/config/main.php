<?php

// TODO:: не знаю, правильно ли запихивать сюда эти константы, может стоит их убрать в отдельный файл

define("MATERIAL_FILE", 1);
define("MATERIAL_LINK", 2);
define("MATERIAL_TORRENT", 3);
define("MATERIAL_TITLE", 4);
define("MATERIAL_TEST", 5);

define("ROLE_STUDENT",0);
define("ROLE_TEACHER",1);
define("ROLE_ADMIN",2);

return array(
	'name'=>'SDO Stimul v 2.0',
	//'defaultController'=>'site',
	'params' => array(
        'timezone' => "Asia/Omsk",
        'roles' => array (ROLE_ADMIN => 'Администратор', ROLE_STUDENT => 'Студент', ROLE_TEACHER => 'Преподаватель'),
    ),
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.classes.*',
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