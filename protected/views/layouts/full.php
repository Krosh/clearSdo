<?php header("Content-Type: text/html; charset=UTF-8");?>
<?php
$cs=Yii::app()->clientScript;
$cs->scriptMap=array(
    'jquery.js'=>false,
    'jquery.ui.js' => false,
);?>

<!doctype html>
<html lang="ru">
<head>

    <META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Стимул</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <!-- <script src="js/less-1.7.5.min.js"></script> -->

    <link rel="shortcut icon" href="../../img/favicons/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="../../img/favicons/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="../../img/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="../../img/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="../../img/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="../../img/favicons/apple-touch-icon-144x144.png" />
</head>
<body>
<header>
    <div class="container">
        <div class="col-group">
            <div class="col-7 col-mb-3">
                <div class="logo">
                    <a href="/">
                        <img src="/img/logo-small.png" height="40" width="40" alt="" data-retina>
                        <span>Стимул</span>
                    </a>
                </div>
                <div class="nav">
                    <div class="small-nav">
                        <img src="/img/icon-menu.png" alt="">
                    </div>
                    <div class="big-nav">
                        <div class="link">
                            <a href="/">Главная</a>
                        </div>
                        <div class="link">
                            <a href="#">Общение</a>
                        </div>
                        <!--  <div class="link">
                              <a href="#">Документы</a>
                          </div>
                        -->  <?php if (Yii::app()->user->isAdmin()):?>
                            <div class="link more">
                                <a href="#">Справочники</a>
                                <div class="more-menu">
                                    <div class="more-menu-links">
                                        <a href="<?php echo $this->createUrl("group/admin")?>"><span>Группы</span></a>
                                        <a href="<?php echo $this->createUrl("user/admin")?>"><span>Пользователи</span></a>
                                        <a href="<?php echo $this->createUrl("term/admin")?>"><span>Периоды</span></a>
                                    </div>
                                </div>
                            </div>
                        <? endif; ?>
                        <?php if (isset(PluginController::$plugins)):?>
                            <div class="link more">
                                <a href="#">Плагины (<?php echo count(PluginController::$plugins)?>)</a>
                                <div class="more-menu">
                                    <div class="more-menu-links">
                                        <?php $i = 0; ?>
                                        <?php foreach (PluginController::$plugins as $item):?>
                                            <a href = "<?php echo $item->getUrl(); ?>"><span><?php echo $item->name?></span></a>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <? endif; ?>
                        <?php if (Yii::app()->user->isTeacher()):?>
                            <div class="link more">
                                <a href="#">Отчеты</a>
                                <div class="more-menu">
                                    <div class="more-menu-links">
                                        <a href="<?php echo $this->createUrl("report/marks")?>"><span>Успеваемость групп</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="link">
                                <a href="<?php echo $this->createUrl("/site/mediateka"); ?>">Медиатека</a>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-5 col-mb-9 right">
                <div class="search">
                    <form action="/search" method="GET">
                        <input type="text" name = "query" placeholder="Поиск" value="<?php echo $_GET['query']; ?>">
                        <button type="submit"><i></i></button>
                    </form>
                </div>

                <div class="user">
                    <div class="language dropdown center">
                        Язык <i class="caret"></i>

                        <div class="dropdown-container">
                            <a href="#"><i class="lang-rus"></i></a>
                            <a href="#"><i class="lang-eng"></i></a>
                            <a href="#"><i class="lang-cn"></i></a>
                        </div>
                    </div>
                    <a href="/message/index" class="mails">
                        <i class="mail"></i>
                        <span>
                            <?php
                                $sql = "SELECT COUNT(id) FROM `tbl_messages` WHERE STATUS = 0 AND idRecepient = ".Yii::app()->user->id;
                                $command = Yii::app()->db->createCommand($sql);
                                echo $command->queryScalar();
                            ?>
                        </span>
                    </a>
                    <div class="profile dropdown">
                        <?php
                        echo Yii::app()->user->getFio();
                        ?>
                        <i class="caret"></i>
                        <div class="dropdown-container">
                            <a href="<?php echo $this->createUrl("/site/userConfig");?>">Настройки</a>
                            <a href="/logout">Выход</a>
                        </div>
                    </div>
                    <div class="avatar">
                         <div class="the-avatar-box" style="background-image: url('<?php echo Yii::app()->user->getAvatar(); ?>')"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<div class="container">
    <?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'homeLink'=>CHtml::link('Главная','/' ),
        )); ?>
    <?php endif?>
</div>



<?php echo $content;  ?>

<div class="col-3">
    <div class="sidebar">

        <div class="sidebar-item">
            <div class="sidebar-title">
                Объявления
            </div>
            <div class="sidebar-content notice">
                <p>Поздравляем всех студентов с началом <strong>Нового Учебного года 2014-15</strong>!</p>
                <p>Тем, кто впервые работает в СДО Стимул рекомендуем ознакомиться с <a href="#">Руководством студента</a>.</p>
                <p>Желаем удачной учебы!</p>
            </div>
        </div>

        <?php $this->renderPartial("/news/block") ?>
        <?php $this->renderPartial("/news/timetable") ?>




    </div>
</div>
</div>
</div>
</div>

<footer class="fixed">
    <div class="container">
        <div class="col-6">
            Copyright © 2008-<?=date("Y")?>, все права защищены <a href="#">СДО Стимул</a>
        </div>
        <div class="col-6 right">
            СДО Стимул 2.0 &nbsp;&nbsp;&nbsp; <a href="#">Разработка системы</a>
        </div>
    </div>
</footer>

<?php if (!isset($this->noNeedJquery) || !$this->noNeedJquery ):?>
    <script src="../../js/jquery-2.1.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
<?php endif; ?>
<script src="../../js/jquery.fs.picker.min.js"></script>
<script src="../../js/jquery.fs.tabber.min.js"></script>
<script src="../../js/jquery.easing.1.3.js"></script>
<script src="../../js/underscore-min.js"></script>
<script src="../../js/moment-with-locales.min.js"></script>
<script src="../../js/clndr.js"></script>
<script src="../../js/timer.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/tinymce/tinymce.min.js"></script>
<!--<script src="../../js/redactor.min.js"></script>-->
<script src="../../js/jquery.quicksearch.js"></script>
<script src="../../js/jquery.multi-select.js"></script>
<script src="../../js/frosty.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/jquery.nicefileinput.min.js"></script>
<script src="../../js/script.js"></script>
</body>
</html>