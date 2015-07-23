<?php
    if(isset($forum)) $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>array_merge(
            $forum->getBreadcrumbs(true),
            array('Новая тема')
        ),
    ));
    else $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>array_merge(
            $thread->getBreadcrumbs(true),
            array('Новый ответ')
        ),
    ));
?>

<div class="form" style="margin:20px;">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'post-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
	),
    )); ?>

    <?php if(isset($forum)): ?>
        <div class="row">
            <?php echo $form->labelEx($model,'subject'); ?>
            <?php echo $form->textField($model,'subject'); ?>
            <?php echo $form->error($model,'subject'); ?>
        </div>
    <?php endif; ?>

        <div class="row">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content', array('rows'=>10, 'cols'=>70)); ?>
            <?php echo $form->error($model,'content'); ?>
            <p class="hint">
                <e>Подсказка</e>: Вы можете использовать <?php echo CHtml::link('markdown', 'http://daringfireball.net/projects/markdown/syntax'); ?>!
            </p>
        </div>

        <?php if(Yii::app()->user->isAdmin): ?>
            <div class="row rememberMe">
                <?php echo $form->checkBox($model,'lockthread', array('uncheckValue'=>0)); ?>
                <?php echo $form->labelEx($model,'lockthread'); ?>
                <?php // echo $form->error($model,'lockthread'); ?>
            </div>
        <?php endif; ?>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Сохранить'); ?>
        </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
