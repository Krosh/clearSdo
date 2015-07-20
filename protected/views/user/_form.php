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
        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'login'); ?>
                <?php echo $form->error($model,'login'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->textField($model,'login',array('size'=>60,'maxlength'=>100)); ?>
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
                <?php echo $form->labelEx($model,'phone'); ?>
                <?php echo $form->error($model,'phone'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php   $this->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'phone',
                    'mask' => '+9-999-999-9999',
                    'placeholder' => '*',
                    'completed' => 'function(){console.log("ok");}',
                )); ?>
            </td>
        </tr>

        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'gender'); ?>
                <?php echo $form->error($model,'gender'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?
                echo $form->dropDownList($model, 'gender', $model->getGenderOptions(), array('empty'=>'Выберите пол...'));
                ?>
            </td>
        </tr>

        <tr>
            <td width="35%">
                <?php echo $form->labelEx($model,'birthday'); ?>
                <?php echo $form->error($model,'birthday'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php   $this->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'birthday',
                    'mask' => '99.99.9999',
                    'placeholder' => '*'
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
                <?php echo $form->labelEx($model,'role'); ?>
                <?php echo $form->error($model,'role'); ?>
            </td>
            <td width="65%" class="input-full-width">
                <?php echo $form->dropDownList($model,'role',Yii::app()->params['roles']); ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <?php if ($model->avatar != ""):?>
                    <div class="the-avatar-box" style="background-image: url('/avatars/<?php echo $model->avatar?>')"></div>
                <?php else: ?>
                    <div class="the-avatar-box" style="background-image: url('/img/avatar-default.png')"></div>
                <?php endif; ?>
            </td>
        </tr>


        <tr>
            <td width="35%" style="vertical-align: middle;">
                <?php //echo $form->labelEx($model,'avatar'); ?>
                <?php echo $form->labelEx($model,'isAvatarModerated'); ?>
            </td>
            <td width="65%" class="input-full-width">
<!--                --><?php /*echo $form->fileField($model,'newAvatar'); */?>
                <?php echo $form->dropDownList($model,'isAvatarModerated',Yii::app()->params['avatarStatuses']); ?>
            </td>
        </tr>


        </table>


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->