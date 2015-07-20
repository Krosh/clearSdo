<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading col-group">
                <div class="col-6">
                    <div class="page-title">Группы</div>
                </div>
            </div>

            <div>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'group-grid',
                    'dataProvider'=>$model->search(),
                    'filter'=>$model,
                    'htmlOptions' => array(
                        'class' => 'table green',
                        'style' => 'width: 100%',
                    ),
                    'columns'=>array(
                        array(
                            'name' => 'Title',
                            'filter' => CHtml::activeTextField($model,"Title", array("placeholder" => "Название")),
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{update} {delete}',
                            'buttons' => array(
                                'update' => array(
                                    'label' => '<i class="fa fa-pencil"></i>',
                                    'imageUrl' => false
                                ),
                                'delete' => array(
                                    'label' => '<i class="fa fa-remove"></i>',
                                    'imageUrl' => false
                                ),
                            ),
                        ),
                    ),
                )); ?>
            </div>
            <a href = "/group/create" class="btn blue">Создать группу</a>

        </div>

    </div>
