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
                    'htmlOptions' => array(
                        'class' => 'table green',
                        'style' => 'width: 100%',
                    ),
                    'columns'=>array(
                        array(
                            'name' => 'fio',
                            'filter' => CHtml::activeTextField($model,"fio", array("placeholder" => "ФИО")),
                        ),
                        array(
                            'name' => 'role',
                            'value' => '$data->getRussianRole()',
                            'filter' => Yii::app()->params['roles'],
                        ),
                        array(
                            'name' => 'login',
                            'filter' => CHtml::activeTextField($model,"login", array("placeholder" => "Логин")),
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{update} {delete}',
                            'buttons' => array(
                                'update' => array(
                                    'label' => '<i class="fa fa-pencil" title="Редактировать"></i>',
                                    'imageUrl' => false
                                ),
                                'delete' => array(
                                    'label' => '<i class="fa fa-remove" title="Удалить"></i>',
                                    'imageUrl' => false
                                ),
                            ),
                        ),
                    ),
                )); ?>
            </div>
            <a href = "/user/create" class="btn blue">Добавить пользователя</a>

        </div>

    </div>
