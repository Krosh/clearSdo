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

    $thCount = 1; // счетчик th
?>
<?php foreach (Course::getCoursesByAutor(Yii::app()->user->id, $currentTerm) as $course):?>
    <?php
    if (isset($courseName) && $courseName != "" && $course->title != $courseName)
        continue;
    ?>
    <h1><?php echo $course->title; ?></h1><br>
    <table width="100%" class="table table-left table-with-border">
        <thead>
            <tr>
                <th>
                    ФИО студента
                </th>
                <?php $controlMaterials = CoursesControlMaterial::getAllControlMaterials($course->id);?>
                <?php foreach ($controlMaterials as $test):?>
                    <th>
                        <?
                        echo $test->title; 
                        $thCount++;
                        ?>
                    </th>
                <?php endforeach?>
            </tr>
        </thead>
        <?php foreach (Group::getGroupsByCourse($course->id,$currentTerm) as $group):?>
            <?php
           if (isset($groupName) && $groupName != "" && $group->Title != $groupName)
                continue;
            echo "<tr><td colspan='".$thCount."'><strong>".$group->Title."</strong></td></tr>";
            foreach ($group->students as $student)
            {
                echo "<tr>";
                echo "<td>".$student->fio."</td>";
                foreach ($controlMaterials as $test)
                {
                    echo "<td>".ControlMaterial::getMark($student->id,$test->id)."</td>";
                }
                echo "</tr>";
            }
            ?>
        <?php endforeach;?>
    </table>
<?php endforeach?>
