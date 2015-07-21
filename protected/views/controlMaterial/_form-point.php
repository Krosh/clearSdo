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
	<table class="remove-width-labels">
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
		<tr>
			<td width="40%">
				<?php echo $form->labelEx($model,'weight'); ?>
			</td>
			<td width="60%">
				<?php echo $form->textField($model,'weight'); ?>
        		<?php echo $form->error($model,'weight'); ?>
			</td>
		</tr>
		<tr>
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
				<?php echo $form->labelEx($model,'is_autocalc'); ?>
			</td>
			<td width="60%">
				<?php echo $form->checkBox($model,'is_autocalc'); ?>
        		<?php echo $form->error($model,'is_autocalc'); ?>
			</td>
		</tr>
		<tr>
			<td width="40%">
				<?php $categories = array(CALC_AUTO => 'Автоматически',CALC_LAUNCH => 'Вручную');?>
	<!--        TODO:: неправильно задаю метку
	-->        <?php echo CHtml::label("Режим расчета ","ControlMaterial['calc_mode']"); ?>
			</td>
			<td width="60%">
				<?php echo $form->dropDownList($model,'calc_mode',$categories); ?>
        		<?php echo $form->error($model,'calc_mode'); ?>
			</td>
		</tr>
        <tr>
            <td width="40%">
                <?php echo $form->labelEx($model,'get_files_from_students'); ?>
            </td>
            <td width="60%">
                <?php echo $form->checkBox($model,'get_files_from_students'); ?>
                <?php echo $form->error($model,'get_files_from_students'); ?>
            </td>
        </tr>
		<tr>
			<td colspan="2">
				<div class="row buttons">
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
				</div>
			</td>
		</tr>
    </table>

<?php $this->endWidget(); ?>


</div><!-- form -->