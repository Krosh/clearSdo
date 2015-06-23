<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 11.10.14
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$currentTerm = Yii::app()->session['currentTerm'];
?>
<?php foreach (Course::getCoursesByAutor(Yii::app()->user->id, $currentTerm) as $course):?>
    <?php
    if (isset($courseName) && $courseName != "" && $course->title != $courseName)
        continue;
    ?>
    <?php
    $table = new TableHelper();
    $table->title = $course->title;
    $table->headerRow = array("Фио студента");
    $controlMaterials = CoursesControlMaterial::getAllControlMaterials($course->id);
    $thCount = 1;
    foreach ($controlMaterials as $test)
    {
        $table->headerRow[] = $test->title;
        $thCount++;

    }
    foreach (Group::getGroupsByCourse($course->id,$currentTerm) as $group)
    {
        if (isset($groupName) && $groupName != "" && $group->Title != $groupName)
            continue;
        $temp = array();
        $temp[] = array("style" => "colspan='".$thCount."'", "text" => "<strong>".$group->Title."</strong>");
        $table->colRows[] = $temp;
        foreach ($group->students as $student)
        {
            $temp = array();
            $temp[] = $student->fio;
            foreach ($controlMaterials as $test)
            {
                $temp[] = ControlMaterial::getMark($student->id,$test->id);
            }
            $table->colRows[] = $temp;
        }

    }
    $table->printTable();
    ?>
<?php endforeach?>
