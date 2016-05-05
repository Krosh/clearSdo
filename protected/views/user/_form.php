<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php
$code = Yii::app()->user->getFlash("codeMessage");
$message = Yii::app()->user->getFlash("message");
if ($model->errorOnSave != "")
{
    $code= "error_duble";
    $message= $model->errorOnSave;
}
?>
<?php if ($code == "success"):?>
    <div class = "success">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if ($code == "error" || $code == "error_duble"):?>
    <div class = "error">
        <?php echo $message; ?>
    </div>
<?php endif; ?>


<?php $form=$this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => "are-you-sure"),
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

    .input-with-radio .picker {
        margin: 0 !important;
    }

    .input-with-radio br {
        display: none;
    }
</style>
<table width="80%">
    <tr>
        <td width="35%" style="vertical-align: middle;">
            <?php echo $form->labelEx($model,'avatar'); ?>
            <?php echo $form->error($model,'avatar'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <div class="col-group">
                <div class="col-3">
                    <?php if ($model->avatar != ""):?>
                        <div class="the-avatar-box big" style="background-image: url('/avatars/<?php echo $model->avatar?>')"></div>
                    <?php else: ?>
                        <div class="the-avatar-box big" style="background-image: url('/img/avatar-default.png')"></div>
                    <?php endif; ?>
                </div>
                <div class="col-9">
                    <?php echo $form->dropDownList($model,'isAvatarModerated',Yii::app()->params['avatarStatuses']); ?>
                </div>
            </div>
            <br>
            <?php echo $form->fileField($model,'newAvatar'); ?>
        </td>
    </tr>
    <tr>
        <td width="35%" style="padding-top:20px">
            <?php echo $form->labelEx($model,'login'); ?>
            <?php echo $form->error($model,'login'); ?>
        </td>
        <td width="65%" class="input-full-width" style="padding-top:20px">
            <?php echo $form->textField($model,'login',array('size'=>60,'maxlength'=>200)); ?>
        </td>
    </tr>

    <?php echo $form->hiddenField($model,"idGroup"); ?>

    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'role'); ?>
            <?php echo $form->error($model,'role'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <?php echo $form->dropDownList($model,'role',Yii::app()->params['roles']); ?>
        </td>
    </tr>

    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'fio'); ?>
            <?php echo $form->error($model,'fio'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <?php echo $form->textField($model,'fio',array('size'=>60,'maxlength'=>200)); ?>
        </td>
    </tr>


    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'phone'); ?>
            <?php echo $form->error($model,'phone'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <?php   $this->widget('CMaskedTextField', array(
                'model' => $model,
                'attribute' => 'phone',
                'mask' => '+7-999-999-9999',
                'placeholder' => '*',
                'completed' => 'function(){console.log("ok");}',
            )); ?>
        </td>
    </tr>
    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->error($model,'email'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>200)); ?>
        </td>
    </tr>


    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'gender'); ?>
            <?php echo $form->error($model,'gender'); ?>
        </td>
        <td width="65%" class="input-with-radio">
            <?
            echo $form->radioButtonList($model, 'gender', $model->getGenderOptions());
            ?>
        </td>
    </tr>

    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'birthday'); ?>
            <?php echo $form->error($model,'birthday'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <?php
            $this->widget('ext.YiiDateTimePicker.jqueryDateTime',array(
                'model'=>$model, //Model object
                'attribute'=>'birthday',
                "options" => array("format" => "d.m.Y", "timepicker" => false, "lang" => "ru")
            ));
            ?>
        </td>
    </tr>

    <tr>
        <td width="35%">
            <?php echo $form->labelEx($model,'info'); ?>
            <?php echo $form->error($model,'info'); ?>
        </td>
        <td width="65%" class="input-full-width">
            <?php echo $form->textArea($model,'info',array('cols'=>75,'maxlength'=>100)); ?>
        </td>
    </tr>

    <table width="80%">
        <?php if ($model->isNewRecord):?>
            <tr>
                <td width="35%">
                    <?php echo $form->labelEx($model,'password'); ?>
                    <?php echo $form->error($model,'password'); ?>
                </td>
                <td width="65%" class="input-full-width">
                    <?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>100,"class" => "js-strength")); ?>
                </td>
            </tr>
        <?php else:?>
            <tr>
                <td width="35%">
                    <?php echo CHTML::label("Новый пароль", "haveNewPassword"); ?>
                    <?php echo CHTML::checkBox("haveNewPassword",$code == "error", array('onclick' => 'checkHasNewPassword()')); ?>
                </td>
                <td width="65%" class="input-full-width">
                </td>
            </tr>
            <tr class = "divNewPassword" <?php if ($code != "error"):?> style="display: none" <?php endif; ?>>
                <td width="35%">
                    <?php echo CHTML::label("Новый пароль:","newPassword")?>
                </td>
                <td width="65%" class="input-full-width">
                    <?php echo CHTML::passwordField("newPassword","", array("class" => "js-strength")); ?>
                </td>
            </tr>
        <?php endif; ?>

    </table>

    <br>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
        <a href="#" onclick="location.reload(); return false;" class="btn gray">Отмена</a>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->