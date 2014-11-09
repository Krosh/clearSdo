<?php
/* @var $this QuestionController */
/* @var $questionModel Question */
/* @var $form CActiveForm */
?>
<div class="form wide">

<?php $_SESSION['userCode'] = 2; ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'question-form',
	'htmlOptions'=>array(
        'class'=>'form inline noborder',
    ),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($questionModel,'content'); ?>
		<?php echo $form->textArea($questionModel,'content',array('rows'=>6, 'cols'=>30, 'class'=>'jsRedactor')); ?>
		<?php echo $form->error($questionModel,'content'); ?>
	</div>
    <br>
    
    <div class="row radio-images">
        <?php $categories = array(1 => '<img src="/img/q1.png" alt="Закрытый">', 2 => '<img src="/img/q2.png" alt="Закрытый с множественным выбором">', 3 => '<img src="/img/q3.png" alt="Числовой ответ">', 4 => '<img src="/img/q4.png" alt="Открытый тест">',5 => '<img src="/img/q5.png" alt="На соответствие">', 6 => '<img src="/img/q5.png" alt="Интерактивный">');   ?>
        <?php echo $form->labelEx($questionModel,'type'); ?>
		<?php echo $form->radioButtonList($questionModel,'type',$categories); ?>
		<?php echo $form->error($questionModel,'type'); ?>
	</div>
    
	<div class="row row-inline">
		<?php echo $form->labelEx($questionModel,'fee'); ?>
		<?php echo $form->textField($questionModel,'fee'); ?>
		<?php echo $form->error($questionModel,'fee'); ?>
	</div>

	<div class="row row-inline">
		<?php echo $form->labelEx($questionModel,'weight'); ?>
		<?php echo $form->numberField($questionModel,'weight'); ?>
		<?php echo $form->error($questionModel,'weight'); ?>
	</div>
	
	<div class="row row-inline">
		<?php echo $form->labelEx($questionModel,'random_answer'); ?>
		<?php echo $form->checkBox($questionModel,'random_answer'); ?>
		<?php echo $form->error($questionModel,'random_answer'); ?>
	</div>

    <script>
        window.idQuestion = <?php echo $questionModel->id; ?>
    </script>
    <div id = "question-answers">
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($questionModel->isNewRecord ? 'Создать' : 'Сохранить', array('onclick' => 'if (!isValidQuestion()) return false;', 'class'=>'btn blue')); ?>
		<a style="margin-left:15px" href = "#" onclick = "addAnswer(<?php echo $questionModel->id; ?>); return false">Добавить вариант</a>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->