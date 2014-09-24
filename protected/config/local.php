<?php

// TODO:: не знаю, правильно ли запихивать сюда эти константы, может стоит их убрать в отдельный файл

define("MATERIAL_FILE", 1);
define("MATERIAL_LINK", 2);
define("MATERIAL_TORRENT", 3);
define("MATERIAL_TITLE", 4);
define("MATERIAL_TEST", 5);


return array(
	'name'=>'SDO Stimul v 2.0',
	//'defaultController'=>'site',
	'params' => array(
        roles => array ('2' => 'Администратор', '0' => 'Студент', '1' => 'Преподаватель'),
    ),
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.classes.*',
        ),
	 
	    'modules'=>array(
	        'gii'=>array(
	            'class'=>'system.gii.GiiModule',
	            'password'=>'1',
	            'ipFilters'=>array(
	                '127.0.0.1',
	                '192.168.90.25'
	                   ),
		 ),
	    ),
	'components'=>array(
			'db'=>array(
		            'class'=>'system.db.CDbConnection',
		            'connectionString'=>'mysql:host=localhost;dbname=sdo',
		            'username'=>'root',
		            'password'=>'',
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