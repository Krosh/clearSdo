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
                    <div class="page-title">Действия пользователей</div>
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
                            'name' => 'tableName',
                            'filter' => CHtml::activeTextField($model,"tableName", array("placeholder" => "Имя таблицы")),
                        ),
                        array(
                            'name' => 'idUser',
                            'value' => '$data->getUserName()',
                            'filter' => CHtml::activeTextField($model,"idUser", array("placeholder" => "Пользователь")),
                        ),
                        array(
                            'name' => 'idAction',
                            'value' => 'Log::$actionNames[$data->idAction]',
                            'filter' => Log::$actionNames,
                        ),
                        array(
                            'name' => 'idRecord',
                            'value' => '$data->idRecord',
                        ),
                        array(
                            'name' => 'dateAction',
                            'value' => 'DateHelper::getRussianDateFromDatabase($data->dateAction,true)',
                        ),
                    ),
                )); ?>
            </div>
        </div>

    </div>
