<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.03.15
 * Time: 21:40
 * To change this template use File | Settings | File Templates.
 */

$userAnswer = UserFileAnswer::model()->find("idUser = :idUser AND idControlMaterial = :idMaterial", array(":idUser" => Yii::app()->user->id,"idMaterial" => $idMaterial));
if ($userAnswer != null)
{
    ?>
    <td colspan='3' style="text-align: center">Файл уже отправлен!<a href = "#" onclick="ajaxDeleteUserFileAnswer(<?php echo $idMaterial; ?>); return false"><i class = "fa fa-remove"></i></a></td>
<?php
} else
{
    ?>
    <td colspan='3' style="text-align: center">
    <form id = 'loadFile'method="POST" enctype="multipart/form-data">
        <input type='file' name = 'filename' onchange="ajaxSendUserFileAnswer(this,<?php echo $idMaterial; ?>)">
    </form>
    </td>
<?php
}
