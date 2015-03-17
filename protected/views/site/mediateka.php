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
            <div>
                <?php
                $criteria = new CDbCriteria();
                $criteria->compare("idAutor", Yii::app()->user->getId());
                $criteria->addInCondition("category",array(MATERIAL_FILE,MATERIAL_TORRENT));
                $data = LearnMaterial::model()->findAll($criteria);
                $dataProvider=new CArrayDataProvider($data, array(
                    'id'=>'name',
                    'sort'=>array(
                        'attributes'=>array(
                            'name', 'ext',
                        ),
                    ),
                    'pagination'=>array(
                        'pageSize'=>10,
                    ),
                ));


                ?>


                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'media-grid',
                    'dataProvider'=>$dataProvider,
                    'columns'=>array(
                        array(
                            'header' => 'Название файла',
                            'name' => 'title',
//                            'filter' => CHtml::dropDownList("sds","",$filters),
                        ),
                        array(
                            'header' => 'Расширение файла',
                            'name' => 'ext',
                            'value' => '$data->getExtension()',
//                            'filter' => CHtml::dropDownList("sds","",$filters),
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{delete}',
                            'buttons' => array(
                                'delete' => array(
                                    'url' => '$this->grid->controller->createUrl("/site/deleteMedia", array("id"=>$data["id"]))',
                                )
                            )
                        ),
                    ),
                )); ?>
            </div>


        </div>
    </div>
