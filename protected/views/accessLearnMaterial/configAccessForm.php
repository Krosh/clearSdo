<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 26.04.15
 * Time: 13:45
 * To change this template use File | Settings | File Templates.
 */
$criteria = new CDbCriteria();
$criteria->compare("idCourse", $idCourse);
$criteria->compare("idLearnMaterial",$idLearnMaterial);
$criteria->compare("type_relation",1);
$commonAccess = AccessLearnMaterial::model()->find($criteria);
if ($commonAccess == null)
{
    LearnMaterial::model()->findByPk($idLearnMaterial)->addCommonAccess($idCourse);
    $commonAccess = AccessLearnMaterial::model()->find($criteria);
}
?>
    <style>
        #editAccessModal label
        {
            width: 100px;
        }
        .dateTimePicker
        {
            width: 120px;
        }
        .row button
        {
            display: none;
        }
    </style>
    По умолчанию: <br>
<?php
$this->renderPartial("/accessLearnMaterial/rowForm", array("model" => $commonAccess, "idCourse" => $idCourse,));
?>
    <br><hr>
    Особые настройки доступа для групп:<br>
<?php
$criteria = new CDbCriteria();
$criteria->compare("idCourse", $idCourse);
$criteria->compare("idLearnMaterial",$idLearnMaterial);
$criteria->compare("type_relation",2);
$access = accessLearnMaterial::model()->findAll($criteria);
foreach ($access as $item)
{
    $this->renderPartial("/accessLearnMaterial/rowForm", array("model" => $item, "idCourse" => $idCourse, "idMaterial" => $idLearnMaterial));
}
echo "<a href = '#' onclick='ajaxAddAccess(".$idCourse.",".$idLearnMaterial.",2)'>Добавить особый доступ</a>";
?>
    <br><hr>
    Особые настройки доступа для пользователей:<br>
<?php
$criteria = new CDbCriteria();
$criteria->compare("idCourse", $idCourse);
$criteria->compare("idLearnMaterial",$idLearnMaterial);
$criteria->compare("type_relation",3);
$access = accessLearnMaterial::model()->findAll($criteria);
foreach ($access as $item)
{
    $this->renderPartial("/accessLearnMaterial/rowForm", array("model" => $item, "idCourse" => $idCourse, "idMaterial" => $idLearnMaterial));
}
echo "<a href = '#' onclick='ajaxAddAccess(".$idCourse.",".$idLearnMaterial.",3)'>Добавить особый доступ</a>";
?>