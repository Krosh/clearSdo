<?php
/* @var $this LearnMaterialController */
/* @var $model LearnMaterial */
/* @var $form CActiveForm */
?>

<div class="form modal-form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
        'id'=>'learnMaterialForm',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->hiddenField($model,'category');?>

    <table style="width: 100%">
        <tr>
            <td>
                <?php echo $form->labelEx($model,'title'); ?>
            </td>
            <td>
                <?php echo $form->textField($model,'title',array('size'=>45)); ?>
                <?php echo $form->error($model,'title'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <div class="fileDiv">
                    <?php echo $form->labelEx($model,'path'); ?>
                </div>
                <div class="linkDiv">
                    <?php echo $form->labelEx($model,'path'); ?>
                </div>
                <div class="dateDiv">
                    <label>Дата начала:</label>
                </div>
            </td>
            <td>
                <div class="fileDiv">
                    <?php echo $form->fileField($model,'path',array('id' => "filePath"))?>
                    <?php echo $form->error($model,'path'); ?>
                </div>
                <div class="linkDiv">
                    <?php echo CHtml::textField("LinkPath",$model->path,array('size'=>45))?>
                    <?php echo $form->error($model,'path'); ?>
                </div>
                <div class="dateDiv">
                    <?php
//                        echo
                    ?>
  <!--                  <?php /*echo $form->textField($model,'path',array('id' => "filePath", 'class' => 'dtPicker'))*/?>
  -->              </div>
            </td>
        </tr>
    </table>




    <div class="row buttons" style="text-align:right; margin-top: 15px;">
        <i style = "display: none" class="fa fa-refresh fa-spin fa-loading-icon" id = "learnMaterialLoader"></i>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array("id" => "learnMaterialSubmitButton","class" => "btn blue", "onclick" => "$(this).hide(); $('#learnMaterialLoader').show();addLearnMaterial($idCourse);closeLearnMaterialDialog(); return false")); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->