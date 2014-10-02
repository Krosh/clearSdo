<?php
/* @var $this QuestionController */
/* @var $questionModel Question */
/* @var $form CActiveForm */
?>
<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'question-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<div class="row">
        <?php $categories = array(1 => 'Закрытый', 2 => 'Закрытый с множественным выбором', 3 => 'Числовой ответ', 4 => 'Открытый тест',5 => 'На соответствие', 6 => 'Интерактивный');   ?>
        <?php echo $form->labelEx($questionModel,'type'); ?>
		<?php echo $form->dropDownList($questionModel,'type',$categories); ?>
		<?php echo $form->error($questionModel,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($questionModel,'content'); ?>
		<?php echo $form->textArea($questionModel,'content',array('rows'=>6, 'cols'=>30)); ?>
		<?php echo $form->error($questionModel,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($questionModel,'fee'); ?>
		<?php echo $form->textField($questionModel,'fee'); ?>
		<?php echo $form->error($questionModel,'fee'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($questionModel,'random_answer'); ?>
		<?php echo $form->checkBox($questionModel,'random_answer'); ?>
		<?php echo $form->error($questionModel,'random_answer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($questionModel,'weight'); ?>
		<?php echo $form->numberField($questionModel,'weight'); ?>
		<?php echo $form->error($questionModel,'weight'); ?>
	</div>

    <script>
        window.idQuestion = <?php echo $questionModel->id; ?>
    </script>
    <div id = "question-answers">
    </div>
    <a href = "" onclick = "addAnswer(<?php echo $questionModel->id; ?>); return false">Добавить вариант ответа</a>

	<div class="row buttons">
		<?php echo CHtml::submitButton($questionModel->isNewRecord ? 'Создать' : 'Сохранить изменения', array('onclick' => 'if (!isValidQuestion()) return false;')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->