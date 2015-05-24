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

	<style>
	.remove-width-labels label, .remove-width-labels input, .remove-width-labels select {
		width: 100%;
	}
	.remove-width-labels td {
		padding-bottom: 10px;
	}
	</style>

	<table width="100%" class="remove-width-labels">
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'title'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'title',array('size'=>30,'maxlength'=>30)); ?>
				<?php echo $form->error($model,'title'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'short_title'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'short_title',array('size'=>30,'maxlength'=>30)); ?>
				<?php echo $form->error($model,'short_title'); ?>
			</td>
		</tr>
<!--		<tr>
			<td width="40%">
				<?php /*echo $form->labelEx($model,'weight'); */?>
			</td>
			<td width="60%">
				<?php /*echo $form->textField($model,'weight'); */?>
        		<?php /*echo $form->error($model,'weight'); */?>
			</td>
		</tr>
-->		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'show_in_reports'); ?>
			</td>
			<td width="60%">
				<?php echo $form->checkBox($model,'show_in_reports'); ?>
        		<?php echo $form->error($model,'show_in_reports'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'dotime'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'dotime'); ?>
				<?php echo $form->error($model,'dotime'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'question_random'); ?>
			</td>
			<td width="60%">
				<?php echo $form->checkBox($model,'question_random'); ?>
				<?php echo $form->error($model,'question_random'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php $categories = array(-1 => 'Все', 2 => '2', 5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25');?>
				<?php echo $form->labelEx($model,'question_show_count'); ?>
			</td>
			<td width="60%">
				<?php echo $form->dropDownList($model,'question_show_count',$categories); ?>
				<?php echo $form->error($model,'question_show_count'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'answer_random'); ?>
			</td>
			<td width="60%">
				<?php echo $form->checkBox($model,'answer_random'); ?>
				<?php echo $form->error($model,'answer_random'); ?>
			</td>
		</tr>
<!--		<tr>
			<td width="40%">
				<?php /*echo $form->labelEx($model,'adaptive'); */?>
			</td>
			<td width="60%">
				<?php /*echo $form->checkBox($model,'adaptive'); */?>
				<?php /*echo $form->error($model,'adaptive'); */?>
			</td>
		</tr>
-->		<tr>
			<td width="40%">
				<?php $categories = array(-1 => 'Неограниченно', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10');?>
				<?php echo $form->labelEx($model,'try_amount'); ?>
			</td>
			<td width="60%">
				<?php echo $form->dropDownList($model,'try_amount',$categories); ?>
				<?php echo $form->error($model,'try_amount'); ?>
			</td>
		</tr>
		<tr>
			<td width="100%" colspan="2" class="label-move-here">
				<style>
				.label-move-here label {
					width: 39.5% !important;
				}
				.label-move-here select, .label-move-here input {
					width: 59.5% !important;
				}
				</style>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'show_answers'); ?>
			</td>
			<td width="60%">
				<?php echo $form->checkBox($model,'show_answers'); ?>
				<?php echo $form->error($model,'show_answers'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php $categories = array(CALC_LAST => 'Последнее',CALC_AVG => 'Среднее', CALC_MIN => 'Минимальное', CALC_MAX => 'Максимальное');?>
        		<?php echo $form->labelEx($model,'calc_mode'); ?>
			</td>
			<td width="60%">
				<?php echo $form->dropDownList($model,'calc_mode',$categories); ?>
        		<?php echo $form->error($model,'calc_mode'); ?>
			</td>
		</tr>
	</table>


    <div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
	</div>

<?php $this->endWidget(); ?>


</div><!-- form -->