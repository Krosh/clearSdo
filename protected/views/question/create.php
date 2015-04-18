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
$listeners = Course::getGroups($model->id);
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">Редактирование вопроса:</div>
                    </div>
                    <?php $this->renderPartial("/question/_form",array("questionModel" => $model)); ?>

                </div>
            </div>
            <div style="display: none">
                <?php
                $mas = array();
                $models = LearnMaterial::model()->findAll("idAutor = -1");
                foreach ($models as $item)
                {
                    $mas[$item->id] = $item->id." ".$item->title;
                }
                $fakeModel = new Question();
                $fakeModel->content = "";
                $this->widget('ext.combobox.EJuiComboBox', array(
                    'model' => $fakeModel,
                    'attribute' => 'content',
                    'data' => $mas,
                    'options' => array(
                        'onSelect' => '
                     $.ajax({
                        type: "POST",
                        url: "/question/addExistQuestion",
                        data: {idQuestion: item.value.split(" ")[0], idControlMaterial:'.$model->id.'},
                        success: function(data)
                        {
                            updateQuestions('.$model->id.')
                            $("#editTest-questionAddExist").hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert("error"+textStatus+errorThrown);
                        }});',
                        'allowText' => false,
                    ),
                    'htmlOptions' => array('size' => 30),
                ));
                ?>
            </div>

