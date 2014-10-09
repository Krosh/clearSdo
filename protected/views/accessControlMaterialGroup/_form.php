<?php
/* @var $this AccessControlMaterialGroupController */
/* @var $model AccessControlMaterialGroup */
/* @var $form CActiveForm */
?>

<div class="form">

	<div class="row">
		<?php $categories = array(1 => 'Открыт', 2 => 'Закрыт', 3 => 'По времени', 4 => 'Последовательно'); ?>
        <?php echo CHtml::label('Доступ',null); ?>
		<?php echo CHtml::dropDownList("Access[access]",$model->access,$categories, array('onchange' => 'updateAccessDivs()')); ?>
	</div>

    <div class = "dateAccess">
        <div class="row">
            <?php echo CHtml::label('Дата начала',null); ?>
            <?php echo CHtml::textField("Access[startDate]",$model->startDate, array ('class' => 'datePicker')); ?>
        </div>

        <div class = "endDateDiv">
            <div class="row">
                <?php echo CHtml::label('Дата окончания',null); ?>
                <?php echo CHtml::textField("Access[endDate]",$model->endDate, array ('class' => 'datePicker')); ?>
           <!--      TODO:: добавить кнопку для очистки даты закрытия
           --> </div>
        </div>
    </div>

    <div class = "beforeAccess">
        <div class="row">
            <?php echo CHtml::label('После прохождения какого теста дать доступ',null); ?>
            <?php
            $controlMaterials = CoursesControlMaterial::getAllControlMaterials(Yii::app()->session['currentCourse'],$idControlMaterial);
            $items = array();
            foreach ($controlMaterials as $item)
            {
                $items[$item->id] = $item->title;
            }
            ?>
            <?php echo CHtml::dropDownList("Access[idBeforeTest]",$model->idBeforeTest,$items); ?>
        </div>

        <div class="row">
            <?php echo CHtml::label('Минимальная оценка',null); ?>
            <?php echo CHtml::textField("Access[minMark]",$model->minMark); ?>
        </div>
    </div>




</div><!-- form -->