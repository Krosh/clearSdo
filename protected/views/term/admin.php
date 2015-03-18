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
                            'columns'=>array(
                                'title',
                                array(
                                    'name' => 'start_date',
                                    'value' => 'DateHelper::getRussianDateFromDatabase($data->start_date)',
                                ),
                                array(
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
                    <a href = "/term/create">Добавить период</a>

                </div>

            </div>
            
