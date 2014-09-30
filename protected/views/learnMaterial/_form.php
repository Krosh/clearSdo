<?php
/* @var $this LearnMaterialController */
/* @var $model LearnMaterial */
/* @var $form CActiveForm */
?>

<div class="form inline">

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

    <div class="row">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'category'); ?>
        <?php $categories = array(1 => 'Файл', 2 => 'Ссылка', 3 => 'Торрент-файл', 4 => "Папка" )   ?>
        <?php echo $form->dropDownList($model,'category',$categories, array('onchange' => 'changeDiv(this.value)')); ?>
        <?php echo $form->error($model,'category'); ?>
    </div>

	<div class="row" id = "fileDiv">
		<?php echo $form->labelEx($model,'path'); ?>
        <?php echo $form->fileField($model,'path',array('id' => "filePath"))?>
		<?php echo $form->error($model,'path'); ?>
	</div>

    <div class="row" id = "linkDiv">
        <?php echo $form->labelEx($model,'path'); ?>
        <?php echo CHtml::textField("LinkPath",$model->path)?>
        <?php echo $form->error($model,'path'); ?>
    </div>

   <!---  Метод для вывода нужного в зависимости от категории */
    /* TODO: перенести процедуру в файл скриптов -->
    <script>
        function changeDiv(n)
        {
            if (n == 2)
            {
                $('#fileDiv').hide();
                $('#linkDiv').show();
            }
            if (n !=4 &&  n != 2)
            {
                $('#fileDiv').show();
                $('#linkDiv').hide();
            }
            if (n == 4)
            {
                $('#fileDiv').hide();
                $('#linkDiv').hide();
            }
        }
        changeDiv(<?php echo $model->category ?>);
    </script>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',array("class" => "btn green", "onclick" => "addLearnMaterial($idCourse);return false")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->