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

<?php
$this->renderPartial('top');
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
                $filters = array("Изображения" => "images");
                $data = Yii::app()->user->getModel()->getFiles("images");
                $model = array("ext" => "", 'name' => "");
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
                            'name' => 'name',
                            'filter' => CHtml::dropDownList("sds","",$filters),
                        ),

                        'ext',
                        array(
                            'class'=>'CButtonColumn',
                            'template' => '{delete}',
                            'buttons' => array(
                                'delete' => array(
                                    'url' => '$this->grid->controller->createUrl("/site/deleteMedia", array("name"=>$data["name"]))',
                                )
                            )
                        ),
                    ),
                )); ?>
            </div>


        </div>
    </div>
<?php
$this->renderPartial("/site/bottom");
?>