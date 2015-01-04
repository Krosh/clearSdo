<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */
/* @var $this CoursesController */
/* @var $courses Array*/
/* @var $isStudent boolean */
/* @var $idTerm int */
?>
<table class="all-courses">
    <?php
    foreach ($courses as $item) {
        ?>
        <?php
        $hasNewLearn = CoursesMaterial::model()->count("idCourse = :idCourse AND dateAdd > :date", array(":idCourse" => $item->id, ":date" => Yii::app()->user->getLastVisit())) > 0;
        $hasNewControl = CoursesControlMaterial::model()->count("idCourse = :idCourse AND dateAdd > :date", array(":idCourse" => $item->id, ":date" => Yii::app()->user->getLastVisit())) > 0;
        $learnMaterialCount = CoursesMaterial::model()->count("idCourse = :idCourse", array(":idCourse" => $item->id));
        $controlMaterialCount = CoursesControlMaterial::model()->count("idCourse = :idCourse", array(":idCourse" => $item->id));
        if ($isStudent)
        {
            $maxProgress = $controlMaterialCount;
            $valProgress = 0;
            $tests = CoursesControlMaterial::model()->findAll("idCourse = :idCourse", array(":idCourse" => $item->id));
            foreach ($tests as $testItem)
            {
                $curMark = ControlMaterial::getMark(Yii::app()->user->id,$testItem->idControlMaterial);
                if ($curMark >= 25) // TODO:: Вынести в конфиг
                $valProgress++;
            }
        } else
        {
            $maxProgress = 0;
            $valProgress = 0;
            foreach (Course::getGroups($item->id,$idTerm) as $group)
            {
                /* @var $group Group*/
                foreach ($group->students as $student)
                {
                    $tests = CoursesControlMaterial::model()->findAll("idCourse = :idCourse", array(":idCourse" => $item->id));
                    foreach ($tests as $testItem)
                    {
                        $curMark = ControlMaterial::getMark($student->id,$testItem->idControlMaterial);
                        if ($curMark >= 25) // TODO:: Вынести в конфиг
                        $valProgress++;
                        $maxProgress++;
                    }

                }
            }
        }
        ?>
        <?
        $url = "/editCourse?idCourse=". $item->id;
        if($isStudent) {
            $url = "/viewCourse?idCourse=". $item->id;
        }
        ?>
        <tr data-href="<?=$url?>">
        <td width="77%">
            <div class="page-title"><?=$item->title?></div>
            <?php if ($isStudent): ?>
                <div class="page-subtitle">Преподаватели:
                    <?
                    $teachers = Course::getAutors($item->id);

                    $arTeachers = array();
                    foreach ($teachers as $teacher){
                        array_push($arTeachers, "<a href = '#'>".$teacher->fio."</a>");
                    }

                    echo implode(', ', $arTeachers);
                    ?>

                </div>
            <?php else: ?>
                <div class="page-subtitle">Группы:
                    <?
                    $groups = Course::getGroups($item->id,$idTerm);

                    $arGroups = array();
                    foreach ($groups as $group){
                        array_push($arGroups, "<a href = '#'>".$group->Title."</a>");
                    }

                    echo implode(', ', $arGroups);
                    ?>

                </div>

            <?php endif ?>

            <div class="progress-bar">
                <div class="progress-bar-title">Прогресс курса: <span><?php echo $valProgress; ?>/<?php echo $maxProgress; ?></span> выполнено</div>

                <div class="progress-out">
                    <div class="progress-in" style="width: <?php if ($maxProgress > 0) echo $valProgress*100/$maxProgress; else echo 0?>%"></div>
                </div>
            </div>
        </td>
        <td>
            <div class="right">
                <div class="course-icons">
                    <div class="course-icon">
                        <div class="ci"><i class="fa fa-file-text <?php if ($hasNewLearn) echo "red"; ?>"></i></div> <a href="<?=$url?>#files"><strong <?php if ($hasNewLearn) echo "class = 'red'"; ?> > <?php echo $learnMaterialCount; ?></strong> файлов</a>
                    </div>
                    <div class="course-icon">
                        <div class="ci"><i class="fa fa-check-square-o <?php if ($hasNewControl) echo "red"; ?>"></i></div> <a href="<?=$url?>#learn"><strong <?php if ($hasNewControl) echo "class = 'red'"; ?> > <?php echo $controlMaterialCount; ?></strong> тестов</a>
                    </div>
                    <div class="course-icon">
                        <div class="ci"><i class="fa fa-comments red"></i></div> <a href="#"><strong class="red">5</strong> сообщений</a>
                    </div>
                </div>
            </div>
        </td>
        </tr>

    <?
    }
    ?>
</table>
