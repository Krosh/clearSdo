<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
    <style>
    .input-full-width input {
        width: 100%;
    }

    .profile-avatar {
        max-width: 100%;
        height: auto;
        border-radius: 50%;
    }
    </style>
    <table width="80%">
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'login'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'login',array('size'=>45,'maxlength'=>45)); ?>
                <?php echo $form->error($model,'login'); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo CHTML::label("Новый пароль", "haveNewPassword"); ?>
                <?php echo CHTML::checkBox("haveNewPassword",false, array('onclick' => 'checkHasNewPassword()')); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo CHTML::passwordField("newPassword","",array("disabled" => true)); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'fio'); ?>
                <?php echo $form->error($model,'fio'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>100)); ?>
            </td>
        </tr>
        <tr>
            <td width="35%">
                <?php if ($model->avatar != ""):?>
                    <img src = "/avatars/<?php echo $model->avatar?>" class="profile-avatar">
                <?php else: ?>
                    <img src = "/img/avatar-default.png" class="profile-avatar">
                <?php endif; ?>
                <?php //echo $form->labelEx($model,'avatar'); ?>
                <?php echo $form->error($model,'avatar'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->fileField($model,'newAvatar'); ?>
                <?php if (!$model->isAvatarModerated):?>
                    <br>
                    <small>Ваш аватар еще не прошел модерацию администратором и поэтому не виден пользователям</small>
                <?php endif;?>
            </td>
        </tr>
    </table>

    <br>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->