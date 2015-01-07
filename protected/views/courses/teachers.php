<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 22:24
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$teachers = Course::getAutors($idCourse);
$arTeachers = array();
if (count($teachers) == 1)
    array_push($arTeachers, "<a href = '#'>".$teachers[0]->fio."</a>");
else
    foreach ($teachers as $teacher){
        array_push($arTeachers, "<a href = '#'>".$teacher->fio."</a> <a href='#' onclick = 'deleteTeacher(".$idCourse.",".$teacher->id."); return false'> <i class='fa fa-remove'></i></a>");
    }

echo implode(', ', $arTeachers);
?>
