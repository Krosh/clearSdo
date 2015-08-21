<?php
/* @var $this CourseController */
/* @var $model Course */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('class' => "are-you-sure"),
)); ?>

	<table class="remove-width-labels">
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'title'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($model,'title'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'discipline'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'discipline',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($model,'discipline'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'description'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($model,'description'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'hours'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'hours'); ?>
				<?php echo $form->error($model,'hours'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="row buttons">
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
					<a href="#" onclick="location.reload(); return false;" class="btn gray">Отмена</a>
				</div>
			</td>
		</tr>
	</table>

<?php $this->endWidget(); ?>

</div><!-- form -->