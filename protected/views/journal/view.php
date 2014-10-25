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
$this->renderPartial('/site/top');
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
<div class="page-heading">
    <div class="col-group">
        <div class="col-4">
            <div class="page-title">
                Курс: <?php echo $course->title?>
                Группа: <?php echo $group->Title;?>
            </div>
        </div>
    </div>

</div>

<hr>

<div class="col-group">
    <div class="col-4">
        <h2>Журнал</h2>
    </div>
</div>
    <?php $this->renderPartial("/journal/table", array("course" => $course, "group" => $group)); ?>
</div>

</div>
<?php
$this->renderPartial("/site/bottom");
?>