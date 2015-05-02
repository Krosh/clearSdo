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
$criteria->compare("idControlMaterial",$idControlMaterial);
$criteria->compare("type_relation",1);
$commonAccess = AccessControlMaterial::model()->find($criteria);
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
$this->renderPartial("/accessControlMaterial/rowForm", array("model" => $commonAccess));
?>
    <br><hr>
    Особые настройки доступа для групп:<br>
<?php
$criteria = new CDbCriteria();
$criteria->compare("idCourse", $idCourse);
$criteria->compare("idControlMaterial",$idControlMaterial);
$criteria->compare("type_relation",2);
$access = AccessControlMaterial::model()->findAll($criteria);
foreach ($access as $item)
{
    $this->renderPartial("/accessControlMaterial/rowForm", array("model" => $item, "idCourse" => $idCourse, "idMaterial" => $idControlMaterial));
}
echo "<a href = '#' onclick='ajaxAddAccess(".$idCourse.",".$idControlMaterial.",2)'>Добавить особый доступ</a>";
?>
    <br><hr>
    Особые настройки доступа для пользователей:<br>
<?php
$criteria = new CDbCriteria();
$criteria->compare("idCourse", $idCourse);
$criteria->compare("idControlMaterial",$idControlMaterial);
$criteria->compare("type_relation",3);
$access = AccessControlMaterial::model()->findAll($criteria);
foreach ($access as $item)
{
    $this->renderPartial("/accessControlMaterial/rowForm", array("model" => $item, "idCourse" => $idCourse, "idMaterial" => $idControlMaterial));
}
echo "<a href = '#' onclick='ajaxAddAccess(".$idCourse.",".$idControlMaterial.",3)'>Добавить особый доступ</a>";
?>