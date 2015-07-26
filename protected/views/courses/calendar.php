<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
/** @var Course $model */
?>

<script>
    window.idCalendarCourse = <?php echo $model->id; ?>
</script>
<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile('/css/calendar/fullcalendar.css');
?>



<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading col-group">
                        <div class="col-6">
                            <div class="page-title">Календарь курса <?php echo $model->title; ?></div>
                        </div>
                    </div>

                    <div id = "calendar">

                    </div>

                </div>

            </div>
            <div class="col-3">
                <div class="sidebar">

                    <div class="sidebar-item">
                        <div class="sidebar-title">
                            Контрольные точки
                        </div>
                        <div class="sidebar-content" id = "eventContainer">
                            <?php foreach ($withoutDateMaterials as $item):?>
                                <div class='fc-event' id = "material<?php echo $item->id; ?>" data-idCourseControlMaterial = "<?php echo $item->id; ?>"><?php echo $item->controlMaterial->title; ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $this->renderPartial("//news/block") ?>
                    <?php $this->renderPartial("//news/timetable") ?>
                </div>
            </div>
