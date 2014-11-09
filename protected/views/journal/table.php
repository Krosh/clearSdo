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
$ids = array();
foreach ($coursesMaterials as $item)
{
    array_push($ids,$item->idControlMaterial);
}
$criteria2 = new CDbCriteria();
$criteria2->addInCondition('id',$ids);
$controlMaterials = ControlMaterial::model()->findAll($criteria2);
?>
<table style="border: 1px solid black">
    <thead>
    <tr>
        <th>
            Студент
        <th>
            <?php foreach ($controlMaterials as $material):?>
        <th style = "border: 1px solid black; padding: 5px">
            <div onclick="showMarksOfMaterial(<?php echo $material->id; ?>)"><?php echo $material->title; ?></div>
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
                    <td style = "border: 1px solid black; padding: 5px; background-color: #0080CC">
                        <div data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>" onclick="showMarkTextbox(<?php echo $student->id; ?>,<?php echo $material->id; ?>)">
                            <?php echo ControlMaterial::getMark($student->id,$material->id); ?>
                        </div>
                        <input data-student="<?php echo $student->id; ?>" data-material = "<?php echo $material->id; ?>" type = "textbox" value = "<?php echo ControlMaterial::getMark($student->id,$material->id); ?>" onChange = "saveMark(<?php echo $student->id?>,<?php echo $material->id; ?>,this.value)" style = "display: none; width: 40px">
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