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

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'post-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
	),
    )); ?>

    

    <style>
    .input-full-width input {
        width: 100%;
    }

    span.required {
        display: inline !important;
        width: auto !important;
        color: red;
    }
    </style>

    <p class="note">Поля, отмеченные <span class="required">звездочкой</span>, обязательны к заполнению.</p>

    <table width="65%">
        <?php if(isset($forum)): ?>
            <tr>
                <td width="35%">
                    <?php echo $form->labelEx($model,'subject'); ?>
                    <?php echo $form->error($model,'subject'); ?>
                </td>
                <td width="65%" class="input-full-width">
                    <?php echo $form->textField($model,'subject'); ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'content'); ?>
                <?php echo $form->error($model,'content'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textArea($model,'content', array('rows'=>10, 'cols'=>70, 'class'=>'jsRedactor', 'style' => 'width:100%; height: 200px')); ?>
            </td>
        </tr>
    
        <?php if(Yii::app()->user->isAdminOnForum()): ?>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'lockthread'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->checkBox($model,'lockthread', array('uncheckValue'=>0)); ?>
            </td>
        </tr>
        <?php endif; ?>


    </table>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить', array("class" => "btn blue", "onClick" => '$("textarea").val(tinyMCE.activeEditor.getContent())')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
