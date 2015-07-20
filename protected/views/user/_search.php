<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");


?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
        <?php echo $form->label($model,'showOnlyNoModerated'); ?>
		<?php echo $form->checkBox($model,'showOnlyNoModerated', array('onchange' => '$(".search-form form").submit()')); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- search-form -->