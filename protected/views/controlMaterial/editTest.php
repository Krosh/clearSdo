<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 01.10.14
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
/* @var @model ControlMaterial */
?>


<?php
$this->renderPartial('/site/top');
?>
    <script>
        window.idTest = <?php echo $model->id; ?>
    </script>
<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">
        <div class="content">
            <div class="page-heading">
                <div class="page-title">Тест: <?php echo $model->title?></div>
                <div>
                    <i class="fa fa-plus-square-o"></i>
                    <a href="#" onclick="$('#editTest-testProperties').slideToggle(); return false;">Редактировать информацию о тесте</a>
                </div>
                <div style = "display: none" id = "editTest-testProperties" class="form inline">
                    <?php $this->renderPartial("/controlMaterial/_form",array("model" => $model))?>
                </div>
            </div>

            <div>
                <a href="<?php echo $this->createUrl("/question/create", array("idMaterial" => $model->id)) ?>">Добавить вопрос</a>
            </div>
 <!--           <div>
                <i class="fa fa-plus-square-o"></i>
                <a href="#" onclick="$('#editTest-questionAddExist').slideToggle(); return false;">Добавить из существующих</a>
            </div>
 -->           <div style = "display: none" id = "editTest-questionAddExist" class="form inline">
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


            <div id = "editTest-questions" style="margin-top:20px">
            </div>


        </div>
    </div>
<?php
$this->renderPartial("/site/bottom");
?>