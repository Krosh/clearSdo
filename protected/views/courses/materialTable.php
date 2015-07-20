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

    $idCurrentHeader = -1;
    ?>
    <?php foreach ($coursesMaterials as $currentCourseMaterial):?>
        <?php $item = LearnMaterial::model()->findByPk($currentCourseMaterial->idMaterial); ?>
        <? if($item->category != MATERIAL_TITLE) { ?>
            <tr id = "<?php echo $currentCourseMaterial->id; ?>"  data-idHeader = "<?php echo $idCurrentHeader; ?>">
    <? } else { ?>
            <?php $idCurrentHeader = $currentCourseMaterial->id; ?>
            <tr id = "<?php echo $currentCourseMaterial->id; ?>" class="titleRow">
        <? } ?>

        <?php if ($item->category == MATERIAL_TITLE):?>
            <td class="title" colspan="3">
                <input id = "editTitle<?php echo $item->id;?>" type="text" onchange="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" onfocusout="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" value = "<?php echo $item->title?>" style="display:none">
                <span id = "labelTitle<?php echo $item->id;?>" onclick="startChangeLearnMaterialTitle(this,<?php echo $item->id;?>)">
                <?
                echo $item->title;
                ?>
                </span>
                <div style="float:right;">
                    <a class="red" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>,<?php echo $currentCourseMaterial->id; ?>); return false"><i class="fa fa-remove"></i></a>
                </div>
            </td>
        <? else: ?>
            <td>
                <?
                $f = $item->getIconExtension();
                ?>
                <img class="file-icon" src="/img/fileicons/<?=$f?>.png" alt="">
                <input id = "editTitle<?php echo $item->id;?>" type="text" onchange="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" onfocusout="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" value = "<?php echo $item->getViewedTitle()?>" style="display:none">
                <span id = "labelTitle<?php echo $item->id;?>" onclick="startChangeLearnMaterialTitle(this,<?php echo $item->id;?>)">
                <?
                echo $item->getViewedTitle();
                ?>
                </span>
            </td>
            <td class="right">
                <?php echo $item->getInfoText(true); ?>
            </td>
            <td class="right">
                <a class="red" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>,<?php echo $currentCourseMaterial->id; ?>); return false"><i class="fa fa-remove"></i></a>
            </td>
        <? endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
