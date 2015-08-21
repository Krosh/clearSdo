<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>array_merge(
            $model->thread->getBreadcrumbs(true),
            array('Ответ')
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
        'htmlOptions' => array('class' => "are-you-sure"),
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
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'content'); ?>
                <?php echo $form->error($model,'content'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textArea($model,'content', array('rows'=>10, 'cols'=>70, 'class'=>'jsRedactor', 'style' => 'width:100%; height: 200px')); ?>
            </td>
        </tr>
    </table>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить', array("class" => "btn blue", "onClick" => '$("textarea").val(tinyMCE.activeEditor.getContent())')); ?>
        <a href="#" onclick="location.reload(); return false;" class="btn gray">Отмена</a>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
