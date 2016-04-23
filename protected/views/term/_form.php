<?php
/* @var $this TermController */
/* @var $model Term */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'term-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions' => array('class' => "are-you-sure"),
    )); ?>

    <table>

        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'title'); ?>
                <?php echo $form->error($model,'title'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'start_date'); ?>
                <?php echo $form->error($model,'start_date'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php 
                $this->widget('zii.widgets.jui.CJuiDatePicker',array( 
                    'model'=>$model, 
                    'attribute'=>'start_date', 
                    'options'=>array( 
                    'dateFormat'=>'dd.mm.yy', 
                ), 
                    'htmlOptions'=>array('value'=>DateHelper::getRussianDateFromDatabase($model->start_date)) 
                )); 
                ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'end_date'); ?>
                <?php echo $form->error($model,'end_date'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php 
                $this->widget('zii.widgets.jui.CJuiDatePicker',array( 
                    'model'=>$model, 
                    'attribute'=>'end_date', 
                    'options'=>array( 
                    'dateFormat'=>'dd.mm.yy', 
                ), 
                    'htmlOptions'=>array('value'=>DateHelper::getRussianDateFromDatabase($model->end_date)) 
                )); 
                ?>
            </td>
        </tr>
    </table>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array("class" => "btn blue")); ?>
        <a href="#" onclick="location.reload(); return false;" class="btn gray">Отмена</a>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->