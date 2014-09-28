<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 22:24
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$groups = Course::getGroups($idCourse,Yii::app()->session['currentTerm']);
$arGroups = array();
foreach ($groups as $group){
    array_push($arGroups, "<a href = '#'>".$group->Title."</a> <a onclick = 'deleteGroup(".$idCourse.",".$group->id."); return false'> X</a>");
}

echo implode(', ', $arGroups);
?>
