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
<?php foreach ($coursesMaterials as $curMaterial):?>
    <?php $item = ControlMaterial::model()->findByPk($curMaterial->idControlMaterial); ?>
    <?php if ($item->is_point) continue; ?>
    <a href = "#" onclick='addLink(<?php echo $item->id.',"'.$item->title.'"';?>)'><?php echo $item->title; ?></a><br>
  <?php endforeach; ?>
