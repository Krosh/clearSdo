<?php
/* @var $this ControlMaterialController */
/* @var $model ControlMaterial */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'control-material-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'short_title'); ?>
		<?php echo $form->textField($model,'short_title',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'short_title'); ?>
	</div>

    <div class="row">
		<?php echo $form->labelEx($model,'dotime'); ?>
		<?php echo $form->textField($model,'dotime'); ?>
		<?php echo $form->error($model,'dotime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'question_random'); ?>
		<?php echo $form->checkBox($model,'question_random'); ?>
		<?php echo $form->error($model,'question_random'); ?>
	</div>


    <div class="row">
        <?php $categories = array(-1 => 'Все', 2 => '2', 5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25');?>
		<?php echo $form->labelEx($model,'question_show_count'); ?>
		<?php echo $form->dropDownList($model,'question_show_count',$categories); ?>
		<?php echo $form->error($model,'question_show_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'answer_random'); ?>
		<?php echo $form->checkBox($model,'answer_random'); ?>
		<?php echo $form->error($model,'answer_random'); ?>
	</div>

   <div class="row">
		<?php echo $form->labelEx($model,'adaptive'); ?>
		<?php echo $form->checkBox($model,'adaptive'); ?>
		<?php echo $form->error($model,'adaptive'); ?>
	</div>

	<div class="row">
        <?php $categories = array(-1 => 'Неограниченно', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10');?>
		<?php echo $form->labelEx($model,'try_amount'); ?>
        <?php echo $form->dropDownList($model,'try_amount',$categories); ?>
		<?php echo $form->error($model,'try_amount'); ?>
	</div>

	<div class="row">
        <?php
        $this->renderPartial('/accessControlMaterialGroup/_form', array('model' => $accessModel));
        ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'show_answers'); ?>
		<?php echo $form->checkBox($model,'show_answers'); ?>
		<?php echo $form->error($model,'show_answers'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_point'); ?>
		<?php echo $form->checkBox($model,'is_point'); ?>
		<?php echo $form->error($model,'is_point'); ?>
	</div>

	<div class="row">
        <?php $categories = array(CALC_LAST => 'Последнее',CALC_AVG => 'Среднее', CALC_MIN => 'Минимальное', CALC_MAX => 'Максимальное');?>
        <?php echo $form->labelEx($model,'calc_mode'); ?>
        <?php echo $form->dropDownList($model,'calc_mode',$categories); ?>
        <?php echo $form->error($model,'calc_mode'); ?>
	</div>


    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn green")); ?>
	</div>

<?php $this->endWidget(); ?>


</div><!-- form -->