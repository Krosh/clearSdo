<?php
/* @var $this GroupController */
/* @var $model Group */

?>
<script>
    window.id_altstu = '<?php echo $model->id_altstu; ?>';
    window.faculty = '<?php echo $model->faculty; ?>';
</script>
<style>
    table.mid td {
        vertical-align: middle;
    }

    .NFI-wrapper {
        display: inline-block !important;
        vertical-align: middle !important;
    }
</style>


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
                        <?php $this->widget('zii.widgets.grid.CGridView', array(
                            'id'=>'group-grid',
                            'dataProvider'=>$model->searchAllStudents(),
                            'htmlOptions' => array(
                                'class' => 'table green',
                                'style' => 'width: 100%',
                            ),
                            'filter'=>null,
                            'columns'=>array(
                                array(
                                    'header' => '№',
                                    'value'  => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + $row + 1',
                                ),
                                array(
                                    'header' => 'ФИО',
                                    'value' => '$data->fio',
                                ),
                                array(
                                    'header' => 'Логин',
                                    'value' => '$data->login',
                                ),
                                array(
                                    'class'=>'CButtonColumn',
                                    'template' => '{update} {delete}',
                                    'deleteConfirmation' => false,
                                    'buttons' => array(
                                        'update' => array(
                                            'url' => 'Yii::app()->createUrl("/user/update?id=$data->id&goToGroup='.$model->id.'")',
                                            'label' => '<i class="fa fa-pencil" title="Редактировать"></i>',
                                            'imageUrl' => false
                                        ),
                                        'delete' => array(
                                            'url' => 'Yii::app()->createUrl("/group/deleteFromGroup?idStudent=$data->id&idGroup='.$model->id.'")',
                                            'label' => '<i class="fa fa-remove" title="Удалить"></i>',
                                            'imageUrl' => false
                                        ),
                                    ),
                                ),
                            ),
                        )); ?>




                        <div>
                            Вы можете добавить студентов в группу тремя способами:
                        </div>
                        <br>
                        <table style="width: 100%" class="mid">
                            <tr>
                                <td>
                                    1) Добавить из существующих в базе:
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;2) Создать нового
                                </td>
                                <td>
                                    3) Добавить пользователей из файла excel<a href = "#" class="has-tip" title="1. Файл должен быть в формате XLS(Книге Excel 97-2003)<br>2.В первых трех столбцах должны содержаться соответственно фамилия, имя и отчество добавляемых учащихся<br>3. Логины студентов будут софрмирован из названия группы транслитом и порядкого номера в файле Excel, пароль равен порядковому номеру в файле Excel">(Подробнее)</a>:
                                </td>
                            </tr>
                            <tr>
                                <td width="40%">
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
                                </td>
                                <td width="20%">
                                    <?php echo CHtml::link('Создать',$this->createUrl("/user/create",array("idGroup" => $model->id)),array("class" => "btn blue")); ?>
                                </td>
                                <td width="40%">
                                    <div style="width: 100%">
                                        <?php
                                        echo CHtml::form("",'post',array("id" => "loadStudentsFromExcelForm"));
                                        ?>
                                        <?php
                                        echo Chtml::fileField("filename");
                                        echo CHtml::hiddenField("idGroup",$model->id);
                                        ?>
                                        <?php
                                        echo Chtml::button("Загрузить", array("onclick" => "loadStudentsFromExcel()", 'class' => 'btn blue small'));
                                        ?>
                                </td>
                                <?php echo CHtml::endForm(); ?>

                    </div>
                    </td>
                    </tr>
                    </table>

                </div>

            </div>
        </div>



