<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 24.10.14
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */
/* @var $course Course */
/* @var $group Group */
?>

<?php
$criteria = new CDbCriteria();
$criteria->compare('idCourse',$idCourse);
$criteria->order = "zindex";
$coursesMaterials = CoursesControlMaterial::model()->findAll($criteria);
$controlMaterials = array();
foreach ($coursesMaterials as $item)
{
    $controlMaterials[] = ControlMaterial::model()->findByPk($item->idControlMaterial);
}
?>
    <table class="table hover-table">
        <thead>
        <tr style="display: table-row!important;">
            <th style="vertical-align:top;">
                Студент
            <th style="vertical-align:top;">
                <?php foreach ($controlMaterials as $material):?>
            <th style="vertical-align:top;">
                <?php echo $material->short_title; ?>
                <br>
                <?php if ($material->is_point):?>
                    <?php if ($material->is_autocalc):?>
                        <?php if ($material->calc_mode == CALC_LAUNCH): ?>
                            <a href="#" style="cursor: pointer;" class="has-tip" title="Рассчитать аттестацию" onclick="recalcMarks(<?php echo $material->id; ?>,<?php echo $group->id; ?>); return false;"><i class="fa fa-calculator"></i></a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="has-tip editMaterialMarks" title="Редактировать" href = '#' data-material = "<?php echo $material->id; ?>" onclick="showMarksOfMaterial(<?php echo $material->id; ?>); return false;"><i class="fa fa-pencil"></i></a>
                        <a style="display: none" class="has-tip saveMaterialMarks" title="Сохранить" href = '#' data-material = "<?php echo $material->id; ?>" onclick="saveMarksOfMaterial(<?php echo $material->id; ?>); return false;"><i class="fa fa-save"></i></a>
                    <?php endif; ?>
                <?php endif; ?>


                <?php if ($material->get_files_from_students && UserFileAnswer::model()->count("idControlMaterial = :id",array(':id' => $material->id))>0): ?>
                    <a class="has-tip" title="Скачать" href = '<?php echo $this->createUrl('/controlMaterial/getUserAnswers', array('idControlMaterial' => $material->id)); ?>' target="_blank"><i class="fa fa-cloud-download"></i></a>
                <?php endif; ?>

            </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <? $n = 0; ?>
        <?php foreach ($group->students as $student): ?>
            <? $n++;?>
            <tr>
                <td>
                    <strong><?=$n?></strong>
                    <?php echo $student->fio;?>
                </td>
                <td>

                </td>
                <?php foreach ($controlMaterials as $material):?>
                    <?php if ($material->is_point):?>
                        <?php if ($material->is_autocalc): ?>
                            <td class="center">
                                <div style="display: inline; " data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>">
                                    <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                                </div>
                            </td>
                        <?php else: ?>
                            <td class="center">
                            <?php
                            $userFileAnswer = UserFileAnswer::model()->find("idUser = :idUser AND idControlMaterial = :idMaterial", array(':idUser' => $student->id, ':idMaterial' => $material->id));
                            ?>
                            <div style="display: inline; cursor:pointer;" class="has-tip" title="Редактировать" data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>" onclick="showMarkTextbox(<?php echo $student->id; ?>,<?php echo $material->id; ?>)">
                                <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                            </div>
                            <input class = "changeOnEnter" data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>" data-autosave = "1" type = "textbox" value = "<?php echo ControlMaterial::getMark($student->id,$material->id, false); ?>" onchange = "saveMark(<?php echo $student->id?>,<?php echo $material->id; ?>,this.value)" onfocusout="$(this).change()" style = "display: none; width: 40px !important">
                            <?php if ($userFileAnswer != null): ?>
                                <a class="has-tip" title="Скачать" href = '<?php echo $this->createUrl('/controlMaterial/getUserAnswer', array('idControlMaterial' => $material->id, 'idUser' => $student->id)); ?>' target="_blank"><i class="fa fa-cloud-download"></i></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        </td>
                    <?php else: ?>
                        <td class="center">
                            <div style="display: inline; " data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>">
                                <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                            </div>
                        </td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php if ($print):?>
    <script>
        window.print();
    </script>
<?php endif; ?>