<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array_merge(
        $model->getBreadcrumbs(!$model->isNewRecord),
        array($model->isNewRecord?'Новая категория':'Редактирование')
    )
));
?>
<div class="form" style="margin:20px;">
<?php $form=$this->beginWidget('CActiveForm'); ?>

    <p class="note">Поля, отмеченные <span class="required">звездочкой</span> обязательны к заполнению.</p>

    <div class="row">
        <?php echo $form->labelEx($model,'parent_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'parent_id', CHtml::listData(
                Forum::model()->findAll(),
                'id', 'title'
            ), array('empty'=>'Верхний уровень')); ?>
        <?php echo $form->error($model,'parent_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title'); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description',array('rows'=>10, 'cols'=>70)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'listorder'); ?>
        <?php echo $form->textField($model,'listorder'); ?>
        <?php echo $form->error($model,'listorder'); ?>
    </div>

    <div class="row rememberMe">
        <?php echo $form->checkBox($model,'is_locked',array('uncheckValue'=>0)); ?>
        <?php echo $form->labelEx($model,'is_locked'); ?>
        <?php // echo $form->error($model,'lockthread'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
