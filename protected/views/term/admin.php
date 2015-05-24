<?php
/* @var $this TermController */
/* @var $model Term */
?>


<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading col-group">
                        <div class="col-6">
                            <div class="page-title">Периоды</div>
                        </div>
                    </div>

                    <div>

                        <?php $this->widget('zii.widgets.grid.CGridView', array(
                            'id'=>'term-grid',
                            'dataProvider'=>$model->search(),
                            'filter'=>$model,
                            'htmlOptions' => array(
                                'class' => 'table green',
                                'style' => 'width: 100%',
                            ),
                            'columns'=>array(
                                array(
                                    'name' => 'title',
                                    'filter' => CHtml::activeTextField($model,"title", array("placeholder" => "Название")),
                                ),
                                array(
                                    'filter' => '',
                                    'name' => 'start_date',
                                    'value' => 'DateHelper::getRussianDateFromDatabase($data->start_date)',
                                ),
                                array(
                                    'filter' => '',
                                    'name' => 'end_date',
                                    'value' => 'DateHelper::getRussianDateFromDatabase($data->end_date)',
                                ),
                                array(
                                    'class'=>'CButtonColumn',
                                    'template' => '{update} {delete}',
                                ),
                            ),
                        )); ?>
                    </div>
                    <a href = "/term/create" class="btn blue">Добавить период</a>

                </div>

            </div>
            
