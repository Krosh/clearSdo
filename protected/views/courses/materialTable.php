<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.09.14
 * Time: 13:14
 * To change this template use File | Settings | File Templates.
 */?>
<table class="table green" id = "learnMaterialTable">
<thead>
<tr>
    <th colspan="2" width="70%" class="left">Файл</th>
    <th></th>
</tr>
</thead>
<tbody>
<?php
$criteria = new CDbCriteria();
$criteria->compare('idCourse',$idCourse);
$criteria->order = "zindex";
$coursesMaterials = CoursesMaterial::model()->findAll($criteria);

?>
<?php foreach ($coursesMaterials as $currentCourseMaterial):?>
    <?php $item = LearnMaterial::model()->findByPk($currentCourseMaterial->idMaterial); ?>
    <? if($item->category != MATERIAL_TITLE) { ?>
        <tr id = "<?php echo $currentCourseMaterial->id; ?>"<!--data-href="--><?php /*echo $this->createUrl("/learnMaterial/getMaterial", array("matId" => $item->id)) */?>">
    <? } else { ?>
        <tr id = "<?php echo $currentCourseMaterial->id; ?>">
    <? } ?>

    <?php if ($item->category == MATERIAL_TITLE):?>
        <td class="title" colspan="3">
            <!-- <i class="fileicon-file"></i> -->
            <?= $item->title;?>
            <div style="float:right;">
                <a class="btn red" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>)"><i class="fa fa-remove"></i></a>
            </div>
        </td>
    <? else: ?>
        <td>
            <?
            $f = $item->getIconExtension();
            ?>
            <img class="file-icon" src="/img/fileicons/<?=$f?>.png" alt="">
            <?
            
            echo $item->title;
            ?>
        </td>
        <td class="right">
            <?php
            $sizeText = "";
            if ($item->category == MATERIAL_FILE)
            {
                $sizeText = $item->getFileSize();
            }
            echo $sizeText;
            ?>

        </td>
        <td class="right">
            <a class="btn red" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>); return false"><i class="fa fa-remove"></i></a>
        </td>
    <? endif; ?>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
