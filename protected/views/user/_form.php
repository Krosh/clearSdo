<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

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

    <div >
        <?php echo CHTML::label("Новый пароль", "haveNewPassword"); ?>
        <?php echo CHTML::checkBox("haveNewPassword",$code == "error", array('onclick' => 'checkHasNewPassword()')); ?>
        <div class  = "divNewPassword" <?php if ($code != "error"):?> style="display: none" <?php endif; ?>>
            <?php echo CHTML::label("Старый пароль:","oldPassword")?><?php echo CHTML::passwordField("oldPassword",""); ?><br>
            <?php echo CHTML::label("Новый пароль:","newPassword")?><?php echo CHTML::passwordField("newPassword",""); ?><br>
            <?php echo CHTML::label("Подтвердите пароль:","newPassword")?><?php echo CHTML::passwordField("confirmNewPassword",""); ?>
        </div>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'fio'); ?>
        <?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>100)); ?>
        <?php echo $form->error($model,'fio'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model,'role'); ?>
        <?php echo $form->dropDownList($model,'role',Yii::app()->params['roles']); ?>
        <?php echo $form->error($model,'role'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'avatar'); ?>
        <?php if ($model->avatar != ""):?>
            <div class="the-avatar-box" style="background-image: url('/avatars/<?php echo $model->avatar?>')"></div>
        <?php else: ?>
            <div class="the-avatar-box" style="background-image: url('/img/avatar-default.png')"></div>
        <?php endif; ?>
        <?php echo $form->fileField($model,'newAvatar'); ?>
        <?php echo $form->error($model,'avatar'); ?>

    </div>


    <div class="row">
        <?php echo $form->labelEx($model,'isAvatarModerated'); ?>
        <?php echo $form->checkBox($model,'isAvatarModerated'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->