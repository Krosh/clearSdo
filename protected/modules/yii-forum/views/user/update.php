<?php
$this->widget('zii.widgets.CBreadcrumbs', array('links'=>array(
    'Форум'=>array('/forum'),
    $model->name=>array('profile', 'idUser'=>$model->id),
    'Update',
)));
?>

<div class="form" style="margin:20px;">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'forumuser-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
	),
    )); ?>

        <div class="row">
            <?php echo $form->labelEx($model,'signature'); ?>
            <?php echo $form->textArea($model,'signature', array('rows'=>5, 'cols'=>70)); ?>
            <?php echo $form->error($model,'signature'); ?>
            <p class="hint">
                <e>Подсказка</e>: Вы можете использовать <?php echo CHtml::link('markdown', 'http://daringfireball.net/projects/markdown/syntax'); ?>!
            </p>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Сохранить'); ?>
        </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
