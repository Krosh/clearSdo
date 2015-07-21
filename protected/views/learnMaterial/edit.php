<?php
/* @var $this LearnMaterialController */
/* @var $model LearnMaterial */
/* @var $form CActiveForm */
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">Редактирование материала:</div>
                    </div>
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

                        <div class="row clearfix">
                            <?php echo $form->labelEx($model,'title'); ?>
                            <?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
                            <?php echo $form->error($model,'title'); ?>
                        </div>
                        <br>

                        <div class="row clearfix">
                            <?php echo $form->labelEx($model,'content'); ?>
                        </div>
                        <br>
                        <div class="row clearfix" style="width: 100%">
                            <?php echo $form->textArea($model,'content',array('class' => 'jsRedactor', 'style' => 'width:100%; height: 400px')); ?>
                        </div>


                        <div class="row buttons" style="text-align:right; margin-top: 15px;">
                            <i style = "display: none" class="fa fa-refresh fa-spin fa-loading-icon" id = "learnMaterialLoader"></i>
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array("id" => "learnMaterialSubmitButton","class" => "btn blue")); ?>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- form -->
                </div>
            </div>
            <script>
                window.currentCourse = <?php echo Yii::app()->session['currentCourse']; ?>;
            </script>
