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
                <input class = "changeOnEnter" id = "editTitle<?php echo $item->id;?>" type="text" onchange="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" onfocusout="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" value = "<?php echo $item->title?>" style="display:none">
                <span id = "labelTitle<?php echo $item->id;?>" onclick="startChangeLearnMaterialTitle(this,<?php echo $item->id;?>)">
                <?
                echo $item->title;
                ?>
                </span>
                <div style="float:right;">
                    <a style="padding-left:10px" href="#" data-target="#editAccessModal" data-toggle="modal" onclick = "ajaxGetAccess(<?php echo $idCourse?>,<?php echo $item->id; ?>,false); return false"><i class="fa <?php echo ($item->getCommonAccess($idCourse)) ? 'fa-unlock': 'fa-lock'?>"></i></a>
                    <a class="red" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>,<?php echo $currentCourseMaterial->id; ?>); return false"><i class="fa fa-remove"></i></a>
                </div>
            </td>
        <? else: ?>
            <td colspan="2">
                <?
                $f = $item->getIconExtension();
                ?>
                <img class="file-icon" src="/img/fileicons/<?=$f?>.png" alt="">
                <input class = "changeOnEnter" id = "editTitle<?php echo $item->id;?>" type="text" onchange="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" onfocusout="ajaxChangeLearnMaterialTitle(this,<?php echo $item->id;?>)" value = "<?php echo $item->getViewedTitle()?>" style="display:none">
                <span id = "labelTitle<?php echo $item->id;?>" onclick="startChangeLearnMaterialTitle(this,<?php echo $item->id;?>)">
                <?
                echo $item->getViewedTitle();
                ?>
                </span>

                <div class="bottoms-info-table">
                    <?php if ($item->category == MATERIAL_LINK): ?>
                        <input class = "changeOnEnter" id = "editLink<?php echo $item->id;?>" type="text" onchange="ajaxChangeLearnMaterialLink(this,<?php echo $item->id;?>)" onfocusout="ajaxChangeLearnMaterialLink(this,<?php echo $item->id;?>)" value = "<?php echo $item->getInfoText(true);?>" style="display:none">
                        <span id = "labelLink<?php echo $item->id;?>" onclick="startChangeLearnMaterialLink(this,<?php echo $item->id;?>)">
                            <?php echo $item->getInfoText(true); ?>
                        </span>
                    <?php else: ?>
                        <?php echo $item->getInfoText(true); ?>
                    <?php endif; ?>
                </div>
            </td>
            <td class="right">
                <a style="padding-left:10px" href="#" data-target="#editAccessModal" data-toggle="modal" onclick = "ajaxGetAccess(<?php echo $idCourse?>,<?php echo $item->id; ?>,false); return false"><i class="fa <?php echo ($item->getCommonAccess($idCourse)) ? 'fa-unlock': 'fa-lock'?>"></i></a>
                <a class="red" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>,<?php echo $currentCourseMaterial->id; ?>); return false"><i class="fa fa-remove"></i></a>
            </td>
        <? endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
