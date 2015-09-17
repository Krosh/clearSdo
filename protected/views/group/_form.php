<?php
/* @var $this GroupController */
/* @var $model Group */
/* @var $form CActiveForm */
?>

<div class="form wide">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'group-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
        'htmlOptions' => array('class' => "are-you-sure"),
    )); ?>


    <?php echo $form->errorSummary($model); ?>

    <table style="width: 100%">
        <tr>
            <td width="35%">
                <?php echo CHtml::activeLabel($model,'faculty', array()); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->dropDownList($model, 'faculty', array("0" => "Выберите...")); ?>
                <i class="fa fa-spinner fa-spin faculty-loader"></i>
            </td>
        </tr>

        <tr>
            <td width="35%">
                <?php echo CHtml::activeLabel($model,'id_altstu', array()); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->dropDownList($model, 'id_altstu', array("0" => "Выберите факультет..."), array("disabled"=>"disabled")); ?>
                <?php //echo $form->textField($model,'Title',array('size'=>20,'maxlength'=>20)); ?>
                <i class="fa fa-spinner fa-spin group-loader" style="display: none;"></i>
            </td>
        </tr>

        <tr>
            <td width="35%">
                <?php echo CHtml::activeLabel($model,'Title', array()); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model, 'Title'); ?>
                <?php //echo $form->textField($model,'Title',array('size'=>20,'maxlength'=>20)); ?>
                <i class="fa fa-spinner fa-spin group-loader" style="display: none;"></i>
            </td>
        </tr>
        <? /* <tr>
            <td width="35%">
                <?php echo CHtml::activeLabel($model,'id_altstu', array()); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'id_altstu',array('size'=>20,'maxlength'=>20)); ?>
                <?php //echo CHtml::button('Получить расписание для группы',array('class' => 'btn blue small', 'onclick' => 'ajaxGetTimetable('.$model->id.');'))?>
            </td>
        </tr>
        */ ?>

        <?php echo $form->hiddenField($model,'id_altstu',array('size'=>20,'maxlength'=>20)); ?>

    </table>
    <br>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class' => 'btn blue')); ?>
        <a href="#" onclick="location.reload(); return false;" class="btn gray">Отмена</a>
    </div>



    <?php $this->endWidget(); ?>

</div><!-- form -->