<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 22:24
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$groups = Course::getGroups($idCourse,$idTerm);
$allGroups = Group::model()->findAll();
$res = "";
foreach ($allGroups as $item)
{
    $criteria = new CDbCriteria();
    $criteria->compare("idCourse",$idCourse);
    $criteria->compare("idGroup",$item->id);
    $criteria->compare("idTerm",$idTerm);
    if (CoursesGroup::model()->count($criteria) == 0)
        $res .= "<option value = '".$item->id."'>".$item->Title."</option>";
    else
        $res .= "<option value = '".$item->id."' selected>".$item->Title."</option>";
}
echo $res;