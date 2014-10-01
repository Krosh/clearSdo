<?php
/* @var $this AccessControlMaterialGroupController */
/* @var $model AccessControlMaterialGroup */
/* @var $form CActiveForm */
?>

<div class="form">
<?php if ($model == null) $model = new AccessControlMaterialGroup() ?>



	<div class="row">
		<?php $categories = array(1 => 'Открыт', 2 => 'Закрыт', 3 => 'По времени', 4 => 'Последовательно'); ?>
        <?php echo CHtml::label('Доступ',null); ?>
		<?php echo CHtml::dropDownList("Access[access]",$model->access,$categories, array('onchange' => 'changeVisibleDivs(this)')); ?>
	</div>

    <div class = "dateAccess">
        <div class="row">
            <?php echo CHtml::label('Дата начала',null); ?>
            <?php echo CHtml::textField("Access[startDate]",$model->startDate, array ('class' => 'datePicker')); ?>
        </div>

        <div class = "row">
            <?php echo CHtml::label('Закрывать доступ', 'hasEndDate') ?>
            <?php $date = new DateTime($model->endDate);?>
            <?php echo CHtml::checkBox('hasEndDate', $date->format("U")>0, array('onclick' => 'changeEndDiv(this)')) ?>
        </div>

        <div class = "endDateDiv">
            <div class="row">
                <?php echo CHtml::label('Дата окончания',null); ?>
                <?php echo CHtml::textField("Access[endDate]",$model->endDate, array ('class' => 'datePicker')); ?>
            </div>
        </div>
    </div>

    <div class = "beforeAccess">
        <div class="row">
            <?php echo CHtml::label('После какого теста дать доступ',null); ?>
            <?php echo CHtml::dropDownList("Access[idBeforeTest]",$model->idBeforeTest,$categories); ?>
        </div>

        <div class="row">
            <?php echo CHtml::label('Минимальная оценка',null); ?>
            <?php echo CHtml::textField("Access[minMark]",$model->minMark); ?>
        </div>
    </div>




</div><!-- form -->