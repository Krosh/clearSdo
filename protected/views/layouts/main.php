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

    <link rel="shortcut icon" href="img/favicons/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="img/favicons/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="../../img/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="../../img/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="../../img/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="../../img/favicons/apple-touch-icon-144x144.png" />
</head>
<?php echo $content;  ?>
<?php if (!isset($this->noNeedJquery) || !$this->noNeedJquery ):?>
<script src="../../js/jquery-2.1.1.min.js"></script>
<?php endif; ?>
<script src="../../js/jquery.fs.picker.min.js"></script>
<script src="../../js/jquery.fs.tabber.min.js"></script>
<script src="../../js/jquery.easing.1.3.js"></script>
<script src="../../js/underscore-min.js"></script>
<script src="../../js/moment-with-locales.min.js"></script>
<script src="../../js/clndr.js"></script>
<script src="../../js/timer.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/redactor.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/script.js"></script>
<script src="//tdcdn.blob.core.windows.net/toolbar/assets/prod/td.js" data-trackduck-id="530191b3800d8ef64be289e5" async=""></script>
</body>
</html>