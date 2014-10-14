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
        <?php echo $form->labelEx($model,'weight'); ?>
        <?php echo $form->textField($model,'weight'); ?>
        <?php echo $form->error($model,'weight'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'show_in_reports'); ?>
        <?php echo $form->checkBox($model,'show_in_reports'); ?>
        <?php echo $form->error($model,'show_in_reports'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'is_autocalc'); ?>
        <?php echo $form->checkBox($model,'is_autocalc'); ?>
        <?php echo $form->error($model,'is_autocalc'); ?>
    </div>

	<div class="row">
        <?php $categories = array(CALC_AUTO => 'Автоматически',CALC_LAUNCH => 'Вручную');?>
<!--        TODO:: неправильно задаю метку
-->        <?php echo CHtml::label("Режим расчета ","ControlMaterial['calc_mode']"); ?>
        <?php echo $form->dropDownList($model,'calc_mode',$categories); ?>
        <?php echo $form->error($model,'calc_mode'); ?>
	</div>


    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
	</div>

<?php $this->endWidget(); ?>


</div><!-- form -->