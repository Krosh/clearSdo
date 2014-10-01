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


<?php
$listeners = Course::getGroups($model->id);
?>
    <script>
        window.idCourse = <?php echo $model->id; ?>
    </script>
<div class="wrapper">
<div class="container">
<div class="col-group">
<div class="col-9">

<div class="content">
    <div class="page-heading">
        <div class="page-title">Курс: <?php echo $model->title?></div>
            <div>
                <i class="fa fa-plus-square-o"></i>
                <a href="#" onclick="$('#editCourse-courseProperties').slideToggle(); return false;">Редактировать информацию о курсе</a>
            </div>
            <div style = "display: none" id = "editCourse-courseProperties" class="form inline">
                <?php $this->renderPartial("/courses/_form",array("model" => $model))?>
            </div>
        <div class="page-subtitle">Преподаватели:
            <div id = "editCourse-teachers" style="display: inline">

            </div>
            <div>
                <i class="fa fa-plus-square-o"></i>
                <a href="#" onclick="$('#editCourse-teacherSelect').slideToggle(); return false;">Добавить преподавателя</a>
            </div>
            <div style = "display: none" id = "editCourse-teacherSelect" class="form inline">
                <?php
                $mas = array();
                $models = User::model()->findAll("role >= ".ROLE_TEACHER);
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
                                    url: "/courses/addTeacherToCourse",
                                    data: {fio: item.value, idCourse:'.$model->id.'},
                                    success: function(data)
                                    {
                                        updateTeachers('.$model->id.')
                                        $("#editCourse-teacherSelect").hide();
                                    },
                                    error: function(jqXHR, textStatus, errorThrown){
                                        alert("error"+textStatus+errorThrown);
                                    }});
                                    ',
                        'allowText' => false,
                    ),
                    'htmlOptions' => array('size' => 30),
                ));

                ?>
            </div>
        </div>


        <div class="page-subtitle">Слушатели:
            <div id = "editCourse-groups" style="display: inline">

            </div>
            <div>
                <i class="fa fa-plus-square-o"></i>
                <a href="#" onclick="$('#editCourse-groupSelect').slideToggle(); return false;">Добавить слушателя</a>
            </div>
            <div style = "display: none" id = "editCourse-groupSelect" class="form inline">
                <?php
                $mas = array();
                $models = Group::model()->findAll();
                foreach ($models as $item)
                {
                    $mas[$item->id] = $item->Title;
                }
                $fakeModel = new Group;
                $fakeModel->Title = "";
                $this->widget('ext.combobox.EJuiComboBox', array(
                    'model' => $fakeModel,
                    'attribute' => 'Title',
                    'data' => $mas,
                    'options' => array(
                        'allowText' => false,
                    ),
                    'htmlOptions' => array('size' => 15),
                ));
                ?>

                <?php
                $mas = array();
                $models = Term::model()->findAll();
                foreach ($models as $item)
                {
                    $mas[$item->id] = $item->title;
                }
                $fakeModel = new Term();
                $fakeModel->title = "";
                $this->widget('ext.combobox.EJuiComboBox', array(
                    'model' => $fakeModel,
                    'attribute' => 'title',
                    'data' => $mas,
                    'options' => array(
                        'allowText' => false,
                    ),
                    'htmlOptions' => array('size' => 15),
                ));
                ?>
                <div style="display: inline-block" class="btn green" onclick='addGroup($("#Group_Title").val(),$("#Term_title").val(),<?php echo $model->id; ?>);' >Добавить</div>

            </div>
        </div>
    </div>


    <h2>Контрольные материалы</h2>
    <div>
        <i class="fa fa-plus-square-o"></i>
        <a href="#" onclick="$('#editCourse-controlMaterialAddExist').slideToggle(); return false;">Добавить из существующих</a>
    </div>
    <div style = "display: none" id = "editCourse-controlMaterialAddExist" class="form inline">
        <?php
        $mas = array();
        $models = ControlMaterial::model()->findAll("idAutor = ".Yii::app()->user->getId());
        foreach ($models as $item)
        {
            $mas[$item->id] = $item->id." ".$item->title;
        }
        $fakeModel = new ControlMaterial();
        $fakeModel->title = "";
        $this->widget('ext.combobox.EJuiComboBox', array(
            'model' => $fakeModel,
            'attribute' => 'title',
            'data' => $mas,
            'options' => array(
                'onSelect' => '
                     $.ajax({
                        type: "POST",
                        url: "/material/addExistMaterial",
                        data: {idMaterial: item.value.split(" ")[0], idCourse:'.$model->id.'},
                        success: function(data)
                        {
                            updateControlMaterials('.$model->id.')
                            $("#editCourse-controlMaterialAddExist").hide();
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
    <div id = "editCourse-controlMaterials" style="margin-top:20px">
    </div>


    <h2>Учебные материалы</h2>
    <div>
        <i class="fa fa-plus-square-o"></i>
        <a href="#" onclick="$('#editCourse-materialAddExist').slideToggle(); return false;">Добавить из существующих</a>
    </div>
    <div style = "display: none" id = "editCourse-materialAddExist" class="form inline">
        <?php
        $mas = array();
        $models = LearnMaterial::model()->findAll("category != ".MATERIAL_TITLE." and idAutor = ".Yii::app()->user->getId());
        foreach ($models as $item)
        {
            $mas[$item->id] = $item->id." ".$item->title;
        }
        $fakeModel = new LearnMaterial();
        $fakeModel->title = "";
        $this->widget('ext.combobox.EJuiComboBox', array(
            'model' => $fakeModel,
            'attribute' => 'title',
            'data' => $mas,
            'options' => array(
                'onSelect' => '
                     $.ajax({
                        type: "POST",
                        url: "/learnMaterial/addExistMaterial",
                        data: {idMaterial: item.value.split(" ")[0], idCourse:'.$model->id.'},
                        success: function(data)
                        {
                            updateLearnMaterials('.$model->id.')
                            $("#editCourse-materialAddExist").hide();
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
    <div>
        <i class="fa fa-plus-square"></i>
        <a href="#" onclick="$('#editCourse-materialAdd').slideToggle(); return false;">Создать материал</a>
    </div>
    <div style = "display: none" id = "editCourse-materialAdd">
        <?php
        $material = new LearnMaterial();
        $this->renderPartial('/learnMaterial/_form', array("idCourse" => $model->id, "model" => $material));
        ?>
    </div>

    <div id = "editCourse-materials" style="margin-top:20px">
    </div>
</div>

</div>
<?php
$this->renderPartial("/site/bottom");
?>