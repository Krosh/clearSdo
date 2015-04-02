<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form wide">

    <?php
    $code = Yii::app()->user->getFlash("codeMessage");
    $message = Yii::app()->user->getFlash("message");
    ?>
    <?php if ($code == "success"):?>
        <div class = "success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($code == "error"):?>
        <div class = "error">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>


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
                <?php echo CHTML::label("Новый пароль", "haveNewPassword"); ?>
                <?php echo CHTML::checkBox("haveNewPassword",$code == "error", array('onclick' => 'checkHasNewPassword()')); ?>
                <div class = "divNewPassword" <?php if ($code != "error"):?> style="display: none" <?php endif; ?>>
                    <?php echo CHTML::label("Старый пароль:","oldPassword")?>
                    <?php echo CHTML::label("Новый пароль:","newPassword")?>
                    <?php echo CHTML::label("Подтвердите пароль:","newPassword")?>
                </div>

            </td>
            <td width="65%" class="input-full-width">
                <br>
                <br>
                <br>
                <div class = "divNewPassword" <?php if ($code != "error"):?> style="display: none" <?php endif; ?>>
                    <?php echo CHTML::passwordField("oldPassword",""); ?>
                    <?php echo CHTML::passwordField("newPassword",""); ?>
                    <?php echo CHTML::passwordField("confirmNewPassword",""); ?>
                </div>
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
                <?php echo $form->labelEx($model,'info'); ?>
                <?php echo $form->error($model,'info'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textArea($model,'info',array('cols'=>65,'maxlength'=>100)); ?>
            </td>
        </tr>
        <tr>
            <td width="35%" style="vertical-align: middle;">
                <?php if ($model->avatar != ""):?>
                    <div class="the-avatar-box" style="background-image: url('/avatars/<?php echo $model->avatar?>')"></div>
                <?php else: ?>
                    <div class="the-avatar-box" style="background-image: url('/img/avatar-default.png')"></div>
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