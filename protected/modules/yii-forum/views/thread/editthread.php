<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>array_merge(
            $model->getBreadcrumbs(true),
            array('Тема')
        ),
    ));
?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'thread-form',
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
                <?php echo $form->labelEx($model,'subject'); ?>
                <?php echo $form->error($model,'subject'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'subject'); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'is_sticky'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->checkBox($model,'is_sticky',array('uncheckValue'=>0)); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'is_locked'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->checkBox($model,'is_locked',array('uncheckValue'=>0)); ?>
            </td>
        </tr>

    </table>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить', array("class" => "btn blue")); ?>
        <a href="#" onclick="location.reload(); return false;" class="btn gray">Отмена</a>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
