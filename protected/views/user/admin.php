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


                <!--TODO :: Перенести эти стили в css-->
                <style>
                    .table .summary
                    {
                        display: none;
                    }
                    .table .items
                    {
                        width: 100%;
                        text-align: center;
                    }
                    .table .filters
                    {
                        background-color: #ECF0F1;
                    }
                </style>


                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'user-grid',
                    'dataProvider'=>$model->search(),
                    'filter'=>$model,
                    'htmlOptions' => array(
                        'class' => 'table green',
                        'style' => 'width: 100%',
                    ),
                    'columns'=>array(
                        'fio',
                        array(
                            'name' => 'role',
                            'value' => '$data->getRussianRole()',
                            'filter' => Yii::app()->params['roles'],
                        ),
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
