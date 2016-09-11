<?php

// TODO:: не знаю, правильно ли запихивать сюда эти константы, может стоит их убрать в отдельный файл

define("MATERIAL_FILE", 1);
define("MATERIAL_LINK", 2);
define("MATERIAL_TORRENT", 3);
define("MATERIAL_TITLE", 4);
define("MATERIAL_TEST", 5);
define("MATERIAL_INBROWSER", 6);
define("MATERIAL_WEBINAR",7);

define("STATUS_PREPARE",1);
define("STATUS_ACTIVE",2);
define("STATUS_END",3);



define("ROLE_GUEST",-1);
define("ROLE_STUDENT",0);
define("ROLE_TEACHER",1);
define("ROLE_ADMIN",2);

define("DEFAULT_AVATAR_PATH","/img/avatar-default.png");

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
    'basePath' => '//pub/home/mvtom/stml2/protected/',
    'name'=>'SDO Stimul v 2.0',
    'sourceLanguage'=>'ru',
    'language'=>'ru',
    //'defaultController'=>'site',
    'params' => array(
        'images' => array("png","bmp","jpg","jpeg","gif"),
        'attendeePW' => 'adsasdasd',
        'moderatorPW' => 'asdsgsdhsa12sd',
        'timezone' => "Asia/Omsk",
        "connectedWithAltSTU" => true,
        'avatarStatuses' => array('Проходит модерацию', 'Одобрен модератором', 'Заблокирован модератором'),
        'roles' => array (ROLE_ADMIN => 'Администратор', ROLE_STUDENT => 'Студент', ROLE_TEACHER => 'Преподаватель'),
    ),

    'import'=>array_merge(array(
        'application.models.*',
        'application.components.*',
        'application.classes.*',
        'application.helpers.*',
        'application.behaviors.*',
        'application.plugins.*',
        'ext.xupload.*',
    ),$result),

    'aliases' => array(
        //If you manually installed it
        'xupload' => 'ext.xupload'
    ),
        'modules'=>array(
        'forum'=>array(
            'class'=>'application.modules.yii-forum.YiiForumModule',
        ),
    ),
    'components'=>array(
        'cache'=>array( 
            'class'=>'system.caching.CFileCache', 
        ),

        'syntaxhighlighter' => array(
            'class' => 'ext.syntaxhighlighter.JMSyntaxHighlighter',
        ),
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
        'imageHandler'=>array('class'=>'CImageHandler'),
        'morphy'=>array(
            'class'=>'ext.phpMorphy.RMorphy',
        ),
        'bigbluebutton'=>array(
            'class'=>'ext.bigbluebutton.BigBlueButton',

            //server and salt provided here are intended for BigBlueButton's testing server
            //do not use it in your real projects

            //security salt - required
            'salt'=>'8cd8ef52e8e101574e400365b55e11a6',
            //API host - required
            'url'=>'http://test-install.blindsidenetworks.com',
            //the rest parameters are optional
            //port is 80 by default
            //'port'=>80,
            //default path to API
            //'path'=>'/bigbluebutton/api/',

            //-you may set default passwords here or set
            // unique passwords for each conference
            //-or even use no passwords — BigBlueButton
            // will assign them randomly in that case

            //common moderator password for any conference
            //'moderatorPW'=>'12345',
            //common attendee password for any conference
            //'attendeePW'=>'123',

            //common url to redirect users after leaving conference,
            //which will be transmitted to Yii::app()->createAbsoluteUrl.
            //default is site root. you may set unique url for each conference.
            //'logoutUrl'=>'/',
        ),
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
        'session' => array(
            'timeout' => 20 * 60,
        ),
        'user'=>array(
            'autoRenewCookie' => true,
            'authTimeout' => 60 * 20,
            'allowAutoLogin'=>true,
            'class' => 'WebUser',
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                'gii'=>'gii',
                'forum'                                         => '/forum/forum/index',
                '/forum/<controller:\w+>/<action:[\w-]+>'        => 'forum/<controller>/<action>',
                '/forum/<action:[\w-]+>'                        => 'forum/forum/<action>',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
                '<action:\w+>' => 'site/<action>',
                'site/<action:\w>'=>'site/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:[0-9a-zA-Z_\-]+>' => '<controller>/<action>',
                '<controller:\w+>' => '<controller>/index',
                //           		'index.php/site' => 'site/index',
            ),
	),
        'request'=>array(
            // Возможно это и костыль, но без него никуда не поехать, тут мы определяем базовый URL нашего приложения.
            'baseUrl'=>$_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'] != $_SERVER['SCRIPT_FILENAME'] ? 'http://'.$_SERVER['HTTP_HOST'] : '',
            // ...
        ),

	),
);