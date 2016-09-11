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
        $sql = "SELECT COUNT(materials.id) FROM tbl_controlmaterials materials INNER JOIN tbl_coursescontrolmaterials ccm ON materials.id = ccm.idControlMaterial WHERE (materials.is_autocalc = 0 OR materials.is_autocalc IS NULL OR materials.is_point <> 1) AND ccm.idCourse = ".$item->id;
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $controlMaterialCount = $command->queryScalar();

        $sql = "SELECT COUNT(materials.id) FROM tbl_learnmaterials materials INNER JOIN tbl_coursesmaterials cm ON materials.id = cm.idMaterial WHERE materials.category <> ".MATERIAL_TITLE." AND cm.idCourse = ".$item->id;
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $learnMaterialCount = $command->queryScalar();

        if ($isStudent)
        {
            $maxProgress = 0;
            $valProgress = 0;
            $tests = CoursesControlMaterial::model()->findAll("idCourse = :idCourse", array(":idCourse" => $item->id));
            foreach ($tests as $testItem)
            {
                if ($testItem->controlMaterial->is_autocalc)
                    continue;
                $curMark = ControlMaterial::getMark(Yii::app()->user->id,$testItem->idControlMaterial);
                if ($curMark >= 25) // TODO:: Вынести в конфиг
                    $valProgress++;
                $maxProgress++;
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
                        if ($testItem->controlMaterial->is_autocalc)
                            continue;
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
        if($isStudent) {
            $url = $this->createUrl("/courses/view",array("id" =>  $item->id));
        } else
        {
            $url = $this->createUrl("/courses/edit",array("id" =>  $item->id));
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
                            array_push($arTeachers, "<a href = '".$this->createUrl("/message/index", array("startDialog" => $teacher->id))."'>".$teacher->fio."</a>");
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
                            <div class="ci"><i class="fa fa-file-text <?php if ($hasNewLearn) echo "red"; ?>"></i></div> <a href="<?=$url?>#files"><strong <?php if ($hasNewLearn) echo "class = 'red'"; ?> > <?php echo $learnMaterialCount; ?> файлов</strong></a>
                        </div>
                        <div class="course-icon">
                            <div class="ci"><i class="fa fa-check-square-o <?php if ($hasNewControl) echo "red"; ?>"></i></div> <a href="<?=$url?>#learn"><strong <?php if ($hasNewControl) echo "class = 'red'"; ?> > <?php echo $controlMaterialCount; ?> тестов</strong></a>
                        </div>
                        <!--                    <div class="course-icon">
                                                <div class="ci"><i class="fa fa-comments red"></i></div> <a href="#"><strong class="red">5 сообщений</strong> </a>
                                            </div>
                        -->                </div>
                </div>
            </td>
        </tr>

    <?
    }
    ?>
</table>
