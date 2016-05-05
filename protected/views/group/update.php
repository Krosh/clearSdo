<?php
/* @var $this GroupController */
/* @var $model Group */

?>
<script>
    window.id_altstu = '<?php echo $model->id_altstu; ?>';
    window.faculty = '<?php echo $model->faculty; ?>';
</script>

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
                        <div style="width: 100%">
                            <?php
                            echo CHtml::form("",'post',array("id" => "loadStudentsFromExcelForm"));
                            ?>
                            <style>
                                table.mid td {
                                    vertical-align: middle;
                                }

                                .NFI-wrapper {
                                    display: inline-block !important;
                                    vertical-align: middle !important;
                                }
                            </style>
                            <table style="width: 100%" class="mid">
                                <tr>
                                    <td width="35%">
                                        Добавить студентов из файла excel:
                                    </td>
                                    <td width="65%" class="input-full-width" style="margin-top: 10px; ">
                                        <?php
                                        echo Chtml::fileField("filename");
                                        echo CHtml::hiddenField("idGroup",$model->id);
                                        ?>
                                        <?php
                                        echo Chtml::button("Загрузить", array("onclick" => "loadStudentsFromExcel()", 'class' => 'btn blue small'));
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <?php echo CHtml::endForm(); ?>

                        </div>
                        <?php $this->widget('zii.widgets.grid.CGridView', array(
                            'id'=>'group-grid',
                            'dataProvider'=>$model->searchAllStudents(),
                            'htmlOptions' => array(
                                'class' => 'table green',
                                'style' => 'width: 100%',
                            ),
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
                                            'label' => '<i class="fa fa-pencil"></i>',
                                            'imageUrl' => false
                                        ),
                                        'delete' => array(
                                            'url' => 'Yii::app()->createUrl("/group/deleteFromGroup?idStudent=$data->id&idGroup='.$model->id.'")',
                                            'label' => '<i class="fa fa-remove"></i>',
                                            'imageUrl' => false
                                        ),
                                    ),
                                ),
                            ),
                        )); ?>

                        <?php echo CHtml::link('Добавить нового пользователя',$this->createUrl("/user/create",array("idGroup" => $model->id)),array("class" => "btn blue")); ?>

                        <br>
                        Добавить существующего пользователя в группу:
                        <!--  --><?php
                        /*                        $mas = array();
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
                                                                $(this).val("");

                                                            },
                                                            error: function(jqXHR, textStatus, errorThrown){
                                                                alert("error"+textStatus+errorThrown);
                                                            }});
                                                            ',
                                                        'allowText' => false,
                                                    ),
                                                    // Options passed to the text input
                                                    'htmlOptions' => array('size' => 30),
                                                ));

                                                */?>
                        <div class = "selectStudent" data-idGroup = "<?php echo $model->id; ?>">
                            <?php
                            echo CHTML::hiddenField("addUserToGroup",-1,array(
                                'onchange' => "$.ajax({
                                              type: 'POST',
                                              url: '/group/addToGroup',
                                              data: {id: this.value, group:".$model->id."},
                                              success: function(data)
                                              {
                                                    $.fn.yiiGridView.update('group-grid');
                                                    $(this).val('');
                                              }});
                                              ",
                            ));
                            ?>
                        </div>

                    </div>

                </div>
            </div>


            
