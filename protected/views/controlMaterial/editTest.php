<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 01.10.14
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
/* @var $model ControlMaterial */
?>


<?php
$this->renderPartial('/site/top');
?>
    <script>
        window.nameModal = "editTestModal";
        <?php
            if ($model->title == "")
                echo "window.needModal = true;" ?>
        window.idTest = <?php echo $model->id; ?>
    </script>
<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">
        <div class="content">
            <div class="page-heading">
                <div class="col-group">
                    <div class="col-4">
                        <div class="page-title">Тест: <?php echo $model->title?></div>
                    </div>
                    <div class="col-8 right">
                        <a href="#" class="btn white small" data-toggle="modal" data-target="#editTestModal"><i class="fa fa-edit"></i> Информация</a>
                    </div>
                </div>


                <!-- редактирование теста -->
                <div class="modal fade" id="editTestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Редактирование информации о контрольном материале</h4>
                            </div>
                            <div class="modal-body">
                                <div id="editTest-testProperties" class="form modal-form">
                                    <?php if (!$model->is_point)
                                        $this->renderPartial("/controlMaterial/_form",array("model" => $model, "accessModel" => $accessModel));
                                    else
                                        $this->renderPartial("/controlMaterial/_form-point",array("model" => $model, "accessModel" => $accessModel));?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php if (!$model->is_point): ?>
                <hr>

                <div class="right">
                    <a class="btn white small" href="<?php echo $this->createUrl("/question/create", array("idMaterial" => $model->id)) ?>"><i class="fa fa-plus"></i> Добавить вопрос</a>
                </div>
                <!--
           <div>
                <i class="fa fa-plus-square-o"></i>
                <a href="#" onclick="$('#editTest-questionAddExist').slideToggle(); return false;">Добавить из существующих</a>
            </div>
           <div style = "display: none" id = "editTest-questionAddExist" class="form inline">
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

-->

                <div id = "editTest-questions" style="margin-top:20px">
                </div>

                <div class="right">
                    <a class="btn white small" href="<?php echo $this->createUrl("/question/create", array("idMaterial" => $model->id)) ?>"><i class="fa fa-plus"></i> Добавить вопрос</a>
                </div>
            <?php else: ?>
                <?php if ($model->is_autocalc): ?>
                    <?php echo CHtml::form(); ?>
                    <?php
                    $expression = $model->calc_expression;
                    $expr_elements = explode(";",$expression);
                    $weights = array();
                    foreach ($expr_elements as $item)
                    {
                        if (strlen(item) == 0)
                            continue;
                        $text = explode("=",$item);
                        $weights[$text[0]] = $text[1];
                    }
                    $arr = CoursesControlMaterial::getAllControlMaterials($idCourse);
                    $res = array();
                    foreach ($arr as $item)
                    {
                        if (!$item->is_autocalc)
                            array_push($res,$item);
                    }
                    ?>
                    <?php foreach($res as $item): ?>
                        <?php echo $item->title; ?> : <input data-idMaterial = <?php echo $item->id; ?> type = "number" value="<?php if ($weights[$item->id] != "") echo $weights[$item->id]; else echo "0";  ?>" min = "0" /><br>

                    <?php endforeach; ?>
                    <?php echo CHtml::button("Сохранить веса", array("onclick" => 'changeWeights('.$model->id.')')); ?>
                    <?php echo CHtml::endForm(); ?>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
<?php
$this->renderPartial("/site/bottom");
?>