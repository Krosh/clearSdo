<?php
/* @var $this UserController */
/* @var $model User */
?>


<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading col-group">
                <div class="col-6">
                    <div class="page-title">Создание пользователя</div>
                </div>
            </div>

            <div>
                <?php $this->renderPartial('_form', array('model'=>$model)); ?>
            </div>

        </div>

    </div>



