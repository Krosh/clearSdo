<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.09.14
 * Time: 13:14
 * To change this template use File | Settings | File Templates.
 */

$criteria = new CDbCriteria();
$criteria->compare('idCourse',$idCourse);
$criteria->order = "zindex";
$coursesMaterials = CoursesControlMaterial::model()->findAll($criteria);


?>
<table class="table green" id = "controlMaterialTable">
    <thead>
    <tr>
        <th width="40%" class="left">Название</th>
        <th>Вопросов</th>
        <th>Время</th>
        <th>Попыток</th>
        <th width="20%"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($coursesMaterials as $curMaterial):?>
        <?php $item = ControlMaterial::model()->findByPk($curMaterial->idControlMaterial); ?>
        <?php ?>
        <tr id = "<?php echo $curMaterial->id?>" data-href = "<?php echo "/controlMaterial/edit?idMaterial=".$item->id?>">
            <?php $num++; ?>
            <td><?php echo $item->title ?></td>
            <?php
            if ($item->question_show_count == -1) $showCount = count(Question::getQuestionsByControlMaterial($item->id)); else $showCount = $mat->question_show_count;
            ?>
            <td class="center"><?php echo $showCount == "" ? "—" : $showCount ?></td>
            <td class="center"><?php echo $item->dotime == "" ? "—" : $item->dotime ?></td>
            <?php
            $tries = UserControlMaterial::model()->findAll('idUser = :idUser and idControlMaterial = :idControlMaterial', array(':idUser' => Yii::app()->user->getId(), ':idControlMaterial' => $item->id));
            $countTries = count($tries);
            ?>
            <td class="center"><?php echo $countTries?> / <?= $item->try_amount == -1 ? '∞' : $item->try_amount ?></td>
             <td class="right">
                 <a style="float:right" onclick="deleteControlMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>)">Удалить</a>
             </td>
        </tr>
    <?
    endforeach ?>
    </tbody>
</table>
