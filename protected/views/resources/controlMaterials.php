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
                        <div class="page-title">Контрольные материалы</div>
                    </div>
                    <div>
                        <div class="search-form">
                            <?php $this->renderPartial('/controlMaterial/_search',array(
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
                                    'header' => 'Название материала',
                                    'name' => 'title',
                                    'value' => '$data->title',
                                    'filter' => CHtml::activeTextField($model,"title", array("placeholder" => "Название")),
                                    'htmlOptions' => array('style' => 'width:60%'),
                                ),
                                array(
                                    'header' => 'Используется в курсах',
                                    'name' => 'courses',
                                    'filter' => '',
                                    'value' => '$data->getCourses()',
                                ),
                                array(
                                    'class'=>'CButtonColumn',
                                    'template' => '{update} {delete}',
                                    'buttons' => array(
                                        'delete' => array(
                                            'label' => '<i class="fa fa-remove"></i>',
                                            'imageUrl' => false,
                                            'url' => '$this->grid->controller->createUrl("/controlMaterial/fullDeleteMaterial", array("id"=>$data["id"]))',
                                        ),
                                        'update' => array(
                                            'label' => '<i class="fa fa-pencil"></i>',
                                            'imageUrl' => false,
                                            'url' => '$this->grid->controller->createUrl("/controlMaterial/edit", array("idMaterial"=>$data["id"]))',
                                        )
                                    )
                                ),
                            ),
                        )); ?>
                    </div>


                </div>
            </div>
