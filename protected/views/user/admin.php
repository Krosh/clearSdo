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
                    <div class="page-title">Пользователи</div>
                </div>
            </div>

            <div>
                <div class="search-form">
                    <?php $this->renderPartial('_search',array(
                        'model'=>$model,
                    )); ?>
                </div>


                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'user-grid',
                    'dataProvider'=>$model->search(),
                    'filter'=>$model,
                    'columns'=>array(
                        'fio',
                        array(
                            'name' => 'role',
                            'value' => '$data->getRussianRole()',
                            'filter' => Yii::app()->params['roles'],
                        ),
                        'avatar',
                        'login',
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{update} {delete}',
                        ),
                    ),
                )); ?>
            </div>
            <a href = "/user/create">Добавить пользователя</a>

        </div>

    </div>
