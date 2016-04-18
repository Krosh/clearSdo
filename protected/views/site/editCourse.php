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
    <div class="page-title">
        Курс: <?php echo $model->title?>
    </div><br>
    <a href="<?php echo $this->createUrl("/site/viewCourse", array("idCourse" => $model->id))?>" ><span>Перейти в режим просмотра</span></a>
    <div id="editCourse-groups"></div>

    <div class="col-group">
        <div class="col-3"></div>
        <div class="col-9 right">
            <div style="vertical-align: middle">
                <div class="btn courses-list dropdown nohover has-tip" data-original-title="Журнал" title="Журнал">
                    <a href="#" class="caret-link">
                        <a href = "#" id = "currentTermTitle"><i class="fa fa-book fa-2x" style="color: #c55a00"></i></a><i class="caret"></i>
                    </a>
                    <div class="dropdown-container">
                        <?php $groups = Group::getGroupsByCourse($model->id, Yii::app()->session["currentTerm"]);
                        foreach ($groups as $item)
                        {
                            echo '<a href="'.$this->createUrl("/site/journal", array("idCourse" => $model->id, "idGroup" => $item->id)).'">'.$item->Title.'</a>';
                        }
                        ?>
                    </div>
                </div>
                <a href="<?php echo $this->createUrl("/courses/calendar", array("id" => $model->id)); ?>" class="btn icon-colored icon-violet has-tip" data-original-title="Календарь" title="Календарь"><i class="fa fa-calendar"></i></a>
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
                        <div class = "selectTeacher" >
                            <?php
                            echo CHTML::hiddenField("addUserToGroup",-1,array(
                                'onchange' => ' $.ajax({
                                                type: "POST",
                                                url: "/courses/addTeacherToCourse",
                                                data: {id: this.value, idCourse:'.$model->id.'},
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
                            ));
                            ?>
                        </div>
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

                        <style>
                            .invisible
                            {
                                display: none !important;
                            }
                        </style>

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

    <!-- редактирование доступа -->
    <div class="modal fade" id="editAccessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i> Настройки доступа</h4>
                </div>
                <div class="modal-body">
                    <div id="editCourse-access" class="form modal-form">
                        <?php
                        // Необходимо вывести виджет, чтобы прогрузились файлы
                        $arr = AccessControlMaterial::model()->findAll();
                        if (count($arr) > 0)
                        {
                            $this->widget('ext.YiiDateTimePicker.jqueryDateTime',array(
                                'model'=>$arr[0], //Model object
                                'attribute'=>'endDate', //attribute name
                                'htmlOptions'=>array('onchange' => 'ajaxUpdateAccess(this);'), // jquery plugin options
                            ));
                        }
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
        <h2 id="files">Учебные материалы</h2>
    </div>
    <div class="col-8 right">
        <?php $this->renderPartial('/learnMaterial/fileLoader'); ?>
        <!-- <a href="#" class="btn white small" data-toggle="modal" data-target="#editUMModal"><i class="fa fa-book"></i> Редактировать</a> -->

        <div class="small-btns">
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Загрузить файл" title="Загрузить файл" onclick="changeDiv('Загрузить файл',<?php echo MATERIAL_FILE; ?>)"><i class="fa fa-upload"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#addexist" data-original-title="Добавить файл из имеющихся" title="Добавить файл из имеющихся"><i class="fa fa-clipboard"></i></a>
            <a href="<?php echo $this->createUrl("/learnMaterial/create", array("idCourse" => $model->id));?>" class="btn small has-tip" data-original-title="Создать файл" title="Создать файл"><i class="fa fa-file-o"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Раздел" title="Раздел" onclick="changeDiv('Создать новый раздел',<?php echo MATERIAL_TITLE; ?>)"><i class="fa fa-folder"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Ссылка" title="Ссылка" onclick="changeDiv('Добавить HTTP-ссылку',<?php echo MATERIAL_LINK; ?>)"><i class="fa fa-link"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Торрент" title="Торрент" onclick="changeDiv('Загрузить торрент-файл',<?php echo MATERIAL_TORRENT; ?>)"><i class="fa fa-magnet"></i></a>
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#loadfile" data-original-title="Вебинар" title="Вебинар" onclick="changeDiv('Зарегистрировать вебинар',<?php echo MATERIAL_WEBINAR; ?>)"><i class="fa fa-microphone"></i></a>
        </div>
    </div>
</div>

<div id = "editCourse-materials" style="margin-top:20px">
</div>

<hr>

<div class="col-group">
    <div class="col-4">
        <h2 id="learn">Контрольные материалы</h2>
    </div>
    <div class="col-8 right">
        <div class="small-btns">
            <a href="#" class="btn small has-tip" data-toggle="modal" data-target="#addCM1" data-original-title="Добавить из существующих" title="Добавить из существующих"><i class="fa fa-clipboard"></i></a>
            <a href="<?php echo $this->createUrl("/controlMaterial/create",array("idCourse" => $model->id, "isPoint" => false)); ?>" class="btn small has-tip" data-original-title="Создать тест" title="Создать тест"><i class="fa fa-check-circle-o"></i></a>
            <a href="<?php echo $this->createUrl("/controlMaterial/create",array("idCourse" => $model->id, "isPoint" => true)); ?>" class="btn small has-tip" data-original-title="Создать контрольную точку" title="Создать контрольную точку"><i class="fa fa-flag"></i></a>
        </div>
    </div>
</div>



<div class="modal fade" id="addCM1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check-square"></i> Контрольные материалы</h4>
            </div>
            <div class="modal-body" style="text-align: center">
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
                                            updateControlMaterials('.$model->id.');
                                            $("#addCM1").modal("hide");
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert("error"+textStatus+errorThrown);
                                        }});',
                            'allowText' => false,
                        ),
                        'htmlOptions' => array('size' => 50),
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
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i><span id = "modalTitle">Загрузить файл</span></h4>
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
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> Добавить файл из имеющихся</h4>
            </div>
            <div class="modal-body">

                <div id = "editCourse-uchMaterialAddExist" class="form modal-form">
                    <style>
                    #learnMaterialPicker_select {
                        width: 45%;
                        display: inline-block;
                        margin-right: 10px;
                    }

                    #learnMaterialPicker {
                        width: 52% !important;
                        display: inline-block;
                        float: right;
                    }
                    </style>

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
                                             updateLearnMaterials('.$model->id.');
                                             $("#addexist").modal("hide");
                                            /*$("#addexist").hide();*/
                                        },
                                        error: function(jqXHR, textStatus, errorThrown){
                                            alert("error"+textStatus+errorThrown);
                                        }});
                                        closeLearnMaterialDialog();',
                            'allowText' => false,
                        ),
                        'htmlOptions' => array('size' => 50, "placeholder" => "Название", 'id' => 'learnMaterialPicker'),
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
