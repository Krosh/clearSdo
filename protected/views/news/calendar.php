<?php
//Yii::app()->clientScript->registerCoreScript('jquery.ui',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile("/js/fullcalendar.min.js",CClientScript::POS_END);
Yii::app()->clientScript->registerCssFile('/css/calendar/fullcalendar_mini.css');
?>
<div class="sidebar-item">
    <div class="sidebar-content">
        <div id = "calendar">
        </div>
    </div>
</div>
