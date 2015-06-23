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
<table style="border: 1px solid black">
    <thead>
    <tr>
        <th>
            Студент
        <th>
            <?php foreach ($controlMaterials as $material):?>
        <th style = "border: 1px solid black; padding: 5px">

            <?php if ($material->get_files_from_students && UserFileAnswer::model()->count("idControlMaterial = :id",array(':id' => $material->id))>0): ?>
                <a href = '<?php echo $this->createUrl('/controlMaterial/getUserAnswers', array('idControlMaterial' => $material->id)); ?>' target="_blank"><i class="fa fa-floppy-o"></i></a>
            <?php endif; ?>

            <?php if ($material->is_autocalc):?>
                <img src="../../../img/is_point.png" onclick="recalcMarks(<?php echo $material->id; ?>,<?php echo $group->id; ?>)">
                <div><?php echo $material->title; ?></div>
            <?php else: ?>
                <div onclick="showMarksOfMaterial(<?php echo $material->id; ?>)"><?php echo $material->short_title; ?></div>
            <?php endif; ?>
        </th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($group->students as $student): ?>
        <tr>
            <td style = "border: 1px solid black; padding: 5px">
                <?php echo $student->fio;?>
            </td>
            <td>

            </td>
            <?php foreach ($controlMaterials as $material):?>
                <?php if ($material->is_point):?>
                    <?php if ($material->is_autocalc): ?>
                        <td style = "border: 1px solid black; padding: 5px;">
                        <div>
                            <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                        </div>
                    <?php else: ?>
                        <td style = "border: 1px solid black; padding: 5px; background-color: #0080CC">
                        <?php
                        $userFileAnswer = UserFileAnswer::model()->find("idUser = :idUser AND idControlMaterial = :idMaterial", array(':idUser' => $student->id, ':idMaterial' => $material->id));
                        ?>
                        <?php if ($userFileAnswer != null): ?>
                            <a href = '<?php echo $this->createUrl('/controlMaterial/getUserAnswer', array('idControlMaterial' => $material->id, 'idUser' => $student->id)); ?>' target="_blank"><i class="fa fa-floppy-o"></i></a>
                        <?php endif; ?>
                        <div style="display: inline" data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>" onclick="showMarkTextbox(<?php echo $student->id; ?>,<?php echo $material->id; ?>)">
                            <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                        </div>
                        <input data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>" type = "textbox" value = "<?php echo ControlMaterial::getMark($student->id,$material->id); ?>" onfocusout = "saveMark(<?php echo $student->id?>,<?php echo $material->id; ?>,this.value)" style = "display: none; width: 40px">
                    <?php endif; ?>

                    </td>
                <?php else: ?>
                    <td style = "border: 1px solid black; padding: 5px">
                        <div id = "markDiv_<?php echo $student->id; ?>_<?php echo $material->id; ?>">
                            <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                        </div>
                    </td>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>