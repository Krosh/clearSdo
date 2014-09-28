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

        <?php if ($isStudent): ?>
            <tr data-href="/viewCourse?idCourse=<?=$item->id?>">
        <?php else: ?>
            <tr data-href="/editCourse?idCourse=<?=$item->id?>">
        <?php endif; ?>
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
                <div class="progress-bar-title">Прогресс курса: <span>0/12</span> выполнено</div>

                <div class="progress-out">
                    <div class="progress-in" style="width: 0%"></div>
                </div>

            </div>
        </td>
        <td>
            <div class="right">
                <div class="course-icons">
                    <div class="course-icon">
                        <div class="ci"><i class="fa fa-file-text"></i></div> <a href="#"><strong>0</strong> новых файлов</a>
                    </div>
                    <div class="course-icon">
                        <div class="ci"><i class="fa fa-wechat"></i></div> <a href="#"><strong>0</strong> новых сообщений</a>
                    </div>
                </div>
            </div>
        </td>
        </tr>

    <?
    }
    ?>
</table>
