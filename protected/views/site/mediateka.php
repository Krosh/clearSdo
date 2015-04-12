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
                <div class="page-title">Медиатека</div>
            </div>
            <a href = "#" onclick="ajaxDeleteAllNonUsedMaterials()">Удалить все неиспользуемые материалы</a>
            <div>
                <div class="search-form">
                    <?php $this->renderPartial('/learnMaterial/_search',array(
                        'model'=>$model,
                    )); ?>
                </div>


                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'media-grid',
                    'dataProvider'=>$model->search(),
                    'filter' => $model,
                    'columns'=>array(
                        array(
                            'header' => 'Название файла',
                            'name' => 'title',
                            'value' => '$data->getViewedTitle()',
                        ),
                        array(
                            'header' => 'Расширение файла',
                            'name' => 'ext',
                            'value' => '$data->getExtension()',
//                            'filter' => CHtml::dropDownList("sds","",$filters),
                        ),
                        array(
                            'header' => 'Используется в курсах',
                            'name' => 'courses',
                            'value' => '$data->getCourses()',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{delete}',
                            'buttons' => array(
                                'delete' => array(
                                    'url' => '$this->grid->controller->createUrl("/learnMaterial/fullDeleteMaterial", array("id"=>$data["id"]))',
                                )
                            )
                        ),
                    ),
                )); ?>
            </div>


        </div>
    </div>
