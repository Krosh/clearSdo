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
        window.nameModal = "editCourseModal";
        <?php
            if ($model->title == "")
                echo "window.needModal = true;"
        ?>
        window.idCourse = <?php echo $model->id; ?>;
        window.idTerm = <?php echo Yii::app()->session["currentTerm"]; ?>;
    </script>
<div class="wrapper">
<div class="container">
<div class="col-group">
<div class="col-9">

<div class="content">
<div class="page-heading">
    <div class="col-group">
        <div class="col-4">
            <div class="page-title">
                Курс: <?php echo $model->title?>
            </div>
            <div id="editCourse-groups"></div>
        </div>
        <div class="col-8 right">
            <div style="vertical-align: middle">
                <a href="#" class="btn icon-colored icon-blue has-tip" data-toggle="modal" data-target="#editCourseModal" data-original-title="Информация" title="Информация"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn icon-colored icon-red has-tip" data-toggle="modal" data-target="#editTeachersModal" data-original-title="Преподаватели" title="Преподаватели"><i class="fa fa-users"></i></a>
                <a href="#" class="btn icon-colored icon-violet has-tip" data-toggle="modal" data-target="#editPeoplesModal" data-original-title="Слушатели" title="Слушатели"><i class="fa fa-graduation-cap"></i></a>
            </div>
        </div>
    </div>

    <!-- редактирование курса -->
    <div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i> Редактирование информации о курсе</h4>
                </div>
                <div class="modal-body">
                    <div id="editCourse-courseProperties" class="form modal-form">
                        <?php $this->renderPartial("/courses/_form",array("model" => $model))?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- редактирование преподавателей -->
    <div class="modal fade" id="editTeachersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i> Преподаватели</h4>
                </div>
                <div class="modal-body">
                    <strong>Преподаватели курса:</strong>
                    <div id="editCourse-teachers"></div>
                </div>
                <hr>
                <div class="modal-body">
                    <strong>Добавить преподавателя:</strong>
                    <div id="editCourse-teacherSelect" class="form modal-form">
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
                                                    /* $("#editCourse-teacherSelect").hide(); */
                                                    $("#User_fio").val(" ")
                                                },
                                                error: function(jqXHR, textStatus, errorThrown){
                                                    alert("error"+textStatus+errorThrown);
                                                }});
                                                ',
                                'allowText' => false,
                            ),
                            'htmlOptions' => array('size' => 45, 'placeholder' => 'Выберите преподавателя'),
                        ));

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- редактирование слушателей -->
    <div class="modal fade" id="editPeoplesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-graduation-cap"></i> Слушатели</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <strong>Добавить слушателя:</strong>

                        <select id='addGroupsSelect' multiple='multiple'>
                        </select>
                        <?php
                        $criteria = new CDbCriteria();
                        $criteria->order = "id DESC";
                        $terms = Term::model()->findAll($criteria);
                        $arr = array();
                        foreach ($terms as $item)
                        {
                            $arr[$item->id] = $item->title;
                        }
                        echo CHtml::dropDownList("termSelect",Yii::app()->session['currentTerm'],$arr, array("class" => "anyclass", "onchange" => 'updateGroups(window.idCourse,$(this).val())'));
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<hr>

<div class="col-group">
    <div class="col-4">
        <h2>Учебные материалы</h2>
    </div>
    <div class="col-8 right">
        <?php $this->renderPartial('/learnMaterial/fileLoader'); ?>
        <!-- <a href="#" class="btn white small" data-toggle="modal" data-target="#editUMModal"><i class="fa fa-book"></i> Редактировать</a> -->

        <div class="small-btns">
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Загрузить файл" title="Загрузить файл" onclick="changeDiv(<?php echo MATERIAL_FILE; ?>)"><i class="fa fa-upload"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#addexist" data-original-title="Добавить файл из имеющихся" title="Добавить файл из имеющихся"><i class="fa fa-clipboard"></i></a>
            <a href="#" class="btn small has-tip" data-original-title="Создать файл" title="Создать файл"><i class="fa fa-file-o"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Раздел" title="Раздел" onclick="changeDiv(<?php echo MATERIAL_TITLE; ?>)"><i class="fa fa-folder"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Ссылка" title="Ссылка" onclick="changeDiv(<?php echo MATERIAL_LINK; ?>)"><i class="fa fa-link"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Торрент" title="Торрент" onclick="changeDiv(<?php echo MATERIAL_TORRENT; ?>)"><i class="fa fa-magnet"></i></a>
        </div>
    </div>
</div>

<div id = "editCourse-materials" style="margin-top:20px">
</div>

<hr>

<div class="col-group">
    <div class="col-4">
        <h2>Контрольные материалы</h2>
    </div>
    <div class="col-8 right">
        <div class="small-btns">
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#addCM1" data-original-title="Добавить из существующих" title="Добавить из существующих"><i class="fa fa-clipboard"></i></a>
            <a href="<?php echo $this->createUrl("/controlMaterial/create",array("idCourse" => $model->id, "isPoint" => false)); ?>" class="btn small has-tip" data-original-title="Создать тест" title="Создать тест"><i class="fa fa-check-circle-o"></i></a>
            <a href="<?php echo $this->createUrl("/controlMaterial/create",array("idCourse" => $model->id, "isPoint" => true)); ?>" class="btn small has-tip" data-original-title="Создать контрольную точку" title="Создать контрольную точку"><i class="fa fa-flag"></i></a>
        </div>-
    </div>
</div>



<div class="modal fade" id="addCM1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check-square"></i> Контрольные материалы</h4>
            </div>
            <div class="modal-body">
                <strong>Добавить из существующих:</strong>

                <div id = "editCourse-controlMaterialAddExist" class="form modal-form">
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
            </div>
        </div>
    </div>
</div>

<div id = "editCourse-controlMaterials" style="margin-top:20px">
</div>

<!-- загрузка файлов -->
<div class="modal fade" id="loadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> Загрузить файл</h4>
            </div>
            <div class="modal-body">

                <div id="editCourse-materialAdd">
                    <?php
                    $material = new LearnMaterial();
                    $this->renderPartial('/learnMaterial/_form', array("idCourse" => $model->id, "model" => $material));
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- добавление имеющихся -->
<div class="modal fade" id="addexist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> Добавить файл из имеющихся</h4>
            </div>
            <div class="modal-body">

                <div id = "editCourse-uchMaterialAddExist" class="form modal-form">
                    <?php
                    $mas = array();
                    $models = LearnMaterial::model()->findAll("category <> ".MATERIAL_TITLE." AND idAutor = ".Yii::app()->user->getId());
                    foreach ($models as $item)
                    {
                        $mas[$item->id] = $item->id." ".$item->title;
                    }
                    $fakeModel = new LearnMaterial();
                    $fakeModel->title = "";
                    $this->widget('ext.combobox.EJuiComboBox', array(
                        'model' => $fakeModel,
                        'id' => 'learnMaterialPicker',
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
                                            /*$("#addexist").hide();*/
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert("error"+textStatus+errorThrown);
                                        }});',
                            'allowText' => false,
                        ),
                        'htmlOptions' => array('size' => 30, "placeholder" => "Название", 'id' => 'learnMaterialPicker'),
                    ));
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

<!--создание файлов -->
<!--создание файлов -->
<!--создание файлов -->
<!--создание файлов -->


</div>

</div>
<?php
$this->renderPartial("/site/bottom");
?>