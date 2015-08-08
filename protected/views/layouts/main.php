<?php header("Content-Type: text/html; charset=UTF-8");?>
<?php
$cs = Yii::app()->clientScript;
$cs->scriptMap = array(
    'jquery.js' => '../../js/jquery.min.js',
    'jquery.min.js' => '../../js/jquery.min.js',
    'jquery-ui.js' => '../../js/jquery.ui.min.js',
    'jquery-ui.min.js' => '../../js/jquery.ui.min.js',
);
$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');
?>

<!doctype html>
<html lang="ru">
<head>

    <META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title class="skiptranslate">Стимул</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <!-- <script src="js/less-1.7.5.min.js"></script> -->

    <link rel="shortcut icon" href="../../img/favicons/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="../../img/favicons/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="../../img/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="../../img/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="../../img/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="../../img/favicons/apple-touch-icon-144x144.png" />
</head>
<?php echo $content;  ?>
<script src="../../js/jquery.fs.picker.min.js"></script>
<script src="../../js/jquery.fs.tabber.min.js"></script>
<script src="../../js/jquery.easing.1.3.js"></script>
<script src="../../js/underscore-min.js"></script>
<script src="../../js/moment-with-locales.min.js"></script>
<script src="../../js/clndr.js"></script>
<script src="../../js/timer.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/tinymce/tinymce.min.js"></script>
<script src="../../js/jquery.quicksearch.js"></script>
<script src="../../js/jquery.multi-select.js"></script>
<script src="../../js/frosty.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/jquery.nicefileinput.min.js"></script>
<script src="../../js/strength.js"></script>
<script src="../../js/jquery.are-you-sure.js"></script>
<script src="../../js/script.js"></script>
</body>
</html>