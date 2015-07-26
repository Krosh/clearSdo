<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array_merge(
        $model->getBreadcrumbs(!$model->isNewRecord),
        array($model->isNewRecord?'Новая категория':'Редактирование')
    )
));
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>

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
                <?php echo $form->labelEx($model,'parent_id'); ?>
                <?php echo $form->error($model,'parent_id'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo CHtml::activeDropDownList($model, 'parent_id', CHtml::listData(
                    Forum::model()->findAll(),
                    'id', 'title'
                ), array('empty'=>'Верхний уровень')); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'title'); ?>
                <?php echo $form->error($model,'title'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'title'); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'description'); ?>
                <?php echo $form->error($model,'description'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textArea($model,'description',array('rows'=>10, 'cols'=>70, 'class'=>'jsRedactor', 'style' => 'width:100%; height: 200px')); ?>
            </td>
        </tr>
<!--         <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'listorder'); ?>
                <?php echo $form->error($model,'listorder'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'listorder'); ?>
            </td>
        </tr> -->
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'is_locked'); ?>
                <?php echo $form->error($model,'is_locked'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->checkBox($model,'is_locked',array('uncheckValue'=>0)); ?>
            </td>
        </tr>
    </table>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
