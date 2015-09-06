<?php header("Content-Type: text/html; charset=UTF-8");?>
<?php
$cs = Yii::app()->clientScript;
$cs->scriptMap = array(
    'jquery.js' => '../../js/jquery.min.js',
    'jquery.min.js' => '../../js/jquery.min.js',
    'jquery-ui.js' => '../../js/jquery.ui.min.js',
    'jquery-ui.min.js' => '../../js/jquery.ui.min.js',
/*    'jquery.ui.widget.js' => false,
    'jquery.ui.widget.min.js' => false,*/
    'jquery.ui.combobox.js' => '../../js/jquery.ui.combobox.js',
    'jquery.ui.combobox.min.js' => '../../js/jquery.ui.combobox.js',
/*    'jquery.ui.combobox.js' => '../../js/jquery.ui.combobox.js',
    'jquery.ui.combobox.min.js' => '../../js/jquery.ui.combobox.js',*/
);
$cs->registerCoreScript('jquery');
$cs->registerScriptFile("/js/moment-with-locales.min.js");
//$cs->registerCoreScript('jquery.ui');
?>
<!doctype html>
<html lang="ru">
<head>

    <META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title class="skiptranslate">Стимул</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <!-- <script src="js/less-1.7.5.min.js"></script> *-->

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
                        <span class="skiptranslate">Стимул</span>
                    </a>
                </div>
                <?php $this->renderPartial("//layouts/menu"); ?>
            </div>

            <?php if (Yii::app()->user->getId() >0):?>
                <?php $this->renderPartial("//layouts/profile");?>
            <?php endif; ?>
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
<?php if (!isset($this->noNeedSidebar) || !$this->noNeedSidebar):?>
    <div class="col-3">
        <div class="sidebar">


            <?php if (Yii::app()->user->getId() >0):?>
                <?php $this->renderPartial("//news/notices") ?>
                <?php $this->renderPartial("//news/calendar") ?>
                <?php $this->renderPartial("//news/block") ?>
                <?php $this->renderPartial("//news/timetable") ?>
            <?php endif; ?>




        </div>
    </div>
<?php endif; ?>
</div>
</div>
</div>

<footer class="fixed">
    <div class="container">
        <div class="col-6">
            Copyright © 2008-<?=date("Y")?>, все права защищены <a href="#" class="skiptranslate">СДО Стимул</a>
        </div>
        <div class="col-6 right">
            <span class="skiptranslate">СДО Стимул 2.0</span> &nbsp;&nbsp;&nbsp; <a href="#">Разработка системы</a>
        </div>
    </div>
</footer>
<?php Yii::app()->syntaxhighlighter->addHighlighter(); ?>
<script src="../../js/jquery.fs.picker.min.js"></script>
<script src="../../js/jquery.fs.tabber.min.js"></script>
<script src="../../js/jquery.maskedinput.min.js"></script>
<script src="../../js/jquery.easing.1.3.js"></script>
<script src="../../js/highcharts/highcharts.js"></script>
<script src="../../js/underscore-min.js"></script>
<script src="../../js/timer.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/tinymce/tinymce.min.js"></script>
<!--<script src="../../js/redactor.min.js"></script>-->
<script src="../../js/jquery.quicksearch.js"></script>
<script src="../../js/jquery.multi-select.js"></script>
<script src="../../js/jquery.ddslick.min.js"></script>
<script src="../../js/frosty.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/jquery.nicefileinput.min.js"></script>
<script src="../../js/strength.js"></script>
<script src="../../js/jquery.are-you-sure.js"></script>
<script src="../../js/script.js"></script>
</body>
</html>