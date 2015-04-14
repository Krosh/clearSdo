<?php
/* @var $this GroupController */
/* @var $model Group */

?>


<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading col-group">
                        <div class="col-6">
                            <div class="page-title">Изменение группы <?php echo $model->Title; ?></div>
                        </div>
                    </div>

                    <div>
                        <?php $this->renderPartial('_form', array('model'=>$model)); ?>
                        <div >
                            Добавить студентов из файла excel:
                            <?php
                            echo CHtml::form("",'post',array("id" => "loadStudentsFromExcelForm"));
                            echo Chtml::fileField("filename");
                            echo CHtml::hiddenField("idGroup",$model->id);
                            echo Chtml::button("Загрузить", array("onclick" => "loadStudentsFromExcel()"));
                            echo CHtml::endForm();
                            ?>

                        </div>
                        <?php $this->widget('zii.widgets.grid.CGridView', array(
                            'id'=>'group-grid',
                            'dataProvider'=>$model->searchAllStudents(),
                            'filter'=>$model,
                            'columns'=>array(
                                array(
                                    'value' => '$data->fio',
                                ),
                                array(
                                    'class'=>'CButtonColumn',
                                    'template' => '{update} {delete}',
                                    'deleteConfirmation' => false,
                                    'buttons' => array(
                                        'update' => array(
                                            'url' => 'Yii::app()->createUrl("/user/update?id=$data->id&goToGroup='.$model->id.'")',
                                        ),
                                        'delete' => array(
                                            'url' => 'Yii::app()->createUrl("/group/deleteFromGroup?idStudent=$data->id&idGroup='.$model->id.'")',
                                        ),
                                    ),
                                ),
                            ),
                        )); ?>

                        <?php
                        $mas = array();
                        $models = User::model()->findAll("role = ".ROLE_STUDENT);
                        foreach ($models as $item)
                        {
                            $mas[$item->id] = $item->fio;
                        }
                        $fakeModel = new User;
                        $fakeModel->fio = "";
                        $this->widget('ext.combobox.EJuiComboBox', array(
                            'model' => $fakeModel,
                            'attribute' => 'fio',
                            'data' => $mas,
                            'options' => array(
                                'onSelect' => '
                                    $.ajax({
                                    type: "POST",
                                    url: "/group/addToGroup",
                                    data: {fio: item.value, group:'.$model->id.'},
                                    success: function(data)
                                    {
                                        $.fn.yiiGridView.update("group-grid");

                                    },
                                    error: function(jqXHR, textStatus, errorThrown){
                                        alert("error"+textStatus+errorThrown);
                                    }});
                                    ',
                                'allowText' => false,
                            ),
                            // Options passed to the text input
                            'htmlOptions' => array('size' => 10),
                        ));

                        ?>

                    </div>

                </div>
            </div>


            
