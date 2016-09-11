<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */
/* @var $model Course */
?>

<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading">
                <div class="page-title">Курсы</div>
            </div>
            <div>
                <div class="search-form">
                    <?php $this->renderPartial('/courses/_search',array(
                        'model'=>$model,
                    )); ?>
                </div>


                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'media-grid',
                    'dataProvider'=>$model->search(),
                    'filter' => $model,
                    'htmlOptions' => array(
                        'class' => 'table green',
                    ),
                    'columns'=>array(
                        array(
                            'header' => 'Название курса',
                            'name' => 'title',
                            'value' => '$data->title',
                            'filter' => CHtml::activeTextField($model,"title", array("placeholder" => "Название")),
                            'htmlOptions' => array('style' => 'width:60%'),
                        ),
                        array(
                            'header' => 'Используется группами',
                            'name' => 'groups',
                            'filter' => '',
                            'value' => '$data->getNameGroups()',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{update} {delete}',
                            'buttons' => array(
                                'delete' => array(
                                    'url' => '$this->grid->controller->createUrl("/courses/fullDeleteCourse", array("id"=>$data["id"]))',
                                    'label' => '<i class="fa fa-remove" title="Удалить"></i>',
                                    'imageUrl' => false
                                ),
                                'update' => array(
                                    'url' => '$this->grid->controller->createUrl("/courses/edit", array("id"=>$data["id"]))',
                                    'label' => '<i class="fa fa-pencil" title="Редактировать"></i>',
                                    'imageUrl' => false
                                )
                            )
                        ),
                    ),
                )); ?>
            </div>


        </div>
    </div>
