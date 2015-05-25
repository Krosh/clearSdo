<?php
/* @var $this QuestionController */
/* @var $questionModel Question */
/* @var $form CActiveForm */
?>
<div class="form wide">

<?php $_SESSION['userCode'] = 2; ?>
   <!-- ЧТО ЭТО??????????????????????????????
-->
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

	<table width="100%">
		<tr>
			<td width="30%">
				<?php echo $form->labelEx($questionModel,'content'); ?>
			</td>
        </tr>
        <tr>
            <style>
                #btn
                {
                    display: none;
                }
            </style>
			<td colspan="2">
				<?php echo $form->textArea($questionModel,'content',array('class'=>'jsRedactor', 'style' => 'width:100%; height: 200px')); ?>
				<?php echo $form->error($questionModel,'content'); ?>
			</td>
		</tr>
		<tr>
			<td width="30%" style="vertical-align: middle;">
//				<?php $categories = array(1 => '<img src="/img/q1.png" alt="Закрытый">', 2 => '<img src="/img/q2.png" alt="Закрытый с множественным выбором">', 3 => '<img src="/img/q3.png" alt="Числовой ответ">', 4 => '<img src="/img/q4.png" alt="Открытый тест">',5 => '<img src="/img/q5.png" alt="На соответствие">', 6 => '<img src="/img/q5.png" alt="Интерактивный">');   ?>
                <?php $categories = array(1 => '<img src="/img/q1.png" alt="Закрытый">', 2 => '<img src="/img/q2.png" alt="Закрытый с множественным выбором">', 3 => '<img src="/img/q3.png" alt="Числовой ответ">', 4 => '<img src="/img/q4.png" alt="Открытый тест">',5 => '<img src="/img/q5.png" alt="На соответствие">');   ?>
                <?php echo $form->labelEx($questionModel,'type'); ?>
			</td>
			<td width="70%" class="horizontal-buttons-list clearfix">
				<br><br>
				<?php echo $form->radioButtonList($questionModel,'type',$categories, array('onchange' => 'updateAnswers(window.idQuestion)')); ?>
				<?php echo $form->error($questionModel,'type'); ?>
			</td>
		</tr>
	</table>
    
	<div class="row row-inline">
<!--		--><?php //echo $form->labelEx($questionModel,'fee'); ?>
<!--		--><?php //echo $form->textField($questionModel,'fee'); ?>
<!--		--><?php //echo $form->error($questionModel,'fee'); ?>
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
		<a style="margin-right:25px" class="btn blue" href = "#" onclick = "addAnswer(<?php echo $questionModel->id; ?>); return false">Добавить вариант</a> <?php echo CHtml::submitButton($questionModel->isNewRecord ? 'Создать' : 'Сохранить', array('onclick' => 'if (!isValidQuestion()) return false;', 'class'=>'btn blue')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->