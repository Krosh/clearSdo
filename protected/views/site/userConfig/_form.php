<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'login'); ?>
		<?php echo $form->textField($model,'login',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'login'); ?>
	</div>

    <div class="row">
        <?php echo CHTML::label("Новый пароль", "haveNewPassword"); ?>
        <?php echo CHTML::checkBox("haveNewPassword",false, array('onclick' => 'checkHasNewPassword()')); ?>
        <?php echo CHTML::passwordField("newPassword","",array("disabled" => true)); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'fio'); ?>
		<?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'fio'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'avatar'); ?>
        <?php if ($model->avatar != ""):?>
            <img src = "/avatars/<?php echo $model->avatar?>" class="profile-avatar">
        <?php else: ?>
            <img src = "/img/avatar-default.png" class="profile-avatar">
        <?php endif; ?>
        <?php echo $form->fileField($model,'newAvatar'); ?>
        <?php echo $form->error($model,'avatar'); ?>

        <?php if (!$model->isAvatarModerated):?>
            <br>
            Ваш аватар еще не прошел модерацию администратором и поэтому не виден пользователям
        <?php endif;?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->