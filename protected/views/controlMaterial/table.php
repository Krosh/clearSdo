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
        <th></th>
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
        <?php if ($item->is_point): ?>
            <tr id = "<?php echo $curMaterial->id?>" data-href = "<?php echo "/controlMaterial/edit?idMaterial=".$item->id?>">
            <td><img class="file-icon" src="/img/fileicons/point.png" alt="">
            </td>
            <td><?php echo $item->title ?></td>
            <td colspan="3" style="text-align: center">
            <?php if ($item->get_files_from_students && UserFileAnswer::model()->count("idControlMaterial = :id",array(':id' => $item->id))>0): ?>
                <a href = '<?php echo $this->createUrl('/controlMaterial/getUserAnswers', array('idControlMaterial' => $item->id)); ?>' target="_blank"><i class="fa fa-cloud-download"></i> Получить архив с работами</a>
            <?php endif; ?>
        </td>
        <td class="right">
            <a style="" href="#" data-target="#editAccessModal" data-toggle="modal" onclick = "ajaxGetControlMaterialAccess(<?php echo $idCourse?>,<?php echo $item->id; ?>); return false"><i class="fa <?php echo ($item->getCommonAccess($idCourse)) ? 'fa-unlock': 'fa-lock'?>"></i></a>
            <a class="red" href="#" onclick="deleteControlMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>,<?php echo $curMaterial->id; ?>); return false"><i class="fa fa-remove"></i></a>
        </td>
        </tr>
        <?php else: ?>
        <tr id = "<?php echo $curMaterial->id?>" data-href = "<?php echo "/controlMaterial/edit?idMaterial=".$item->id?>">
            <td><img class="file-icon" src="/img/fileicons/test.png" alt="">
            </td>
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
                <a href="<?php echo $this->createUrl("/controlMaterial/statistic", array("idMaterial" => $item->id)); ?>"><i class="fa fa-search"></i></a>
                <a href="#" data-target="#editAccessModal" data-toggle="modal" onclick = "ajaxGetAccess(<?php echo $idCourse?>,<?php echo $item->id; ?>,true); return false"><i class="fa <?php echo ($item->getCommonAccess($idCourse)) ? 'fa-unlock': 'fa-lock'?>"></i></a>
                <a class="red" href="#" onclick="deleteControlMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>,<?php echo $curMaterial->id; ?>); return false"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>
