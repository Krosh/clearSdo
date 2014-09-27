<?php
/* @var $this UserController */
/* @var $model User */

?>

<?php $this->renderPartial('/site/top'); ?>

<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading col-group">
                <div class="col-6">
                    <div class="page-title">Изменение пользователя <?php echo $model->fio; ?></div>
                </div>
            </div>

            <div>
                <?php $this->renderPartial('_form', array('model'=>$model)); ?>
            </div>

        </div>

    </div>

<?php $this->renderPartial('/site/bottom'); ?>

