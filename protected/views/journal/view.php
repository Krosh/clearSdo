<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 24.10.14
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */
/* @var $course Course */
/* @var $group Group */
?>

<?php
$listeners = Course::getGroups($course->id);
?>
    <script>
        window.idCourse = <?php echo $course->id; ?>
    </script>
<div class="wrapper">
<div class="container">
<div class="col-group">
<div class="col-9">

<div class="content">
<hr>

<div class="col-group">
    <div class="col-4">
        <h2>Журнал</h2>
    </div>
    <div class="col-8 right">
        <i class="fa fa-refresh fa-spin fa-loading-icon" style="display: none;"></i>
        <a href="<?php echo $this->createUrl("/site/journal", array("idCourse" => $course->id, "idGroup" => $group->id, "print" => true))?>" class="page-print"><i class="print"></i> <span>Версия для печати</span></a>
    </div>
</div>
    <div id = "journal_table">
        <?php $this->renderPartial("/journal/table", array("idCourse" => $course->id, "group" => $group)); ?>
    </div>
</div>

</div>
