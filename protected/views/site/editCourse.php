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
        <div class="col-group">
            <div class="col-4">
                <div class="page-title">
                    Курс: <?php echo $model->title?>
                </div>
            </div>
            <div class="col-8 right">
                <div style="vertical-align: middle">
                    <a href="#" class="btn white small" data-toggle="modal" data-target="#editCourseModal"><i class="fa fa-edit"></i> Информация</a>
                    <a href="#" class="btn white small" data-toggle="modal" data-target="#editTeachersModal"><i class="fa fa-users"></i> Преподаватели</a>
                    <a href="#" class="btn white small" data-toggle="modal" data-target="#editPeoplesModal"><i class="fa fa-graduation-cap"></i> Слушатели</a>
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
                                'htmlOptions' => array('size' => 30),
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
            			    <strong>Слушатели курса:</strong>
                			<div id="editCourse-groups"></div>
            			</div>
            			<hr>
            			<div class="modal-body">
            			    <strong>Добавить слушателя:</strong>
            			    <div id="editCourse-groupSelect" class="form modal-form">
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
                                <div style="display: inline-block" class="btn blue" onclick='addGroup($("#Group_Title").val(),$("#Term_title").val(),<?php echo $model->id; ?>);' >Добавить</div>
                
                            </div>
            			</div>
        			</div>
        		</div>
        	</div>
        </div>
        
    </div>

    <hr>

    <div class="col-group">
        <div class="col-4">
            <h2>Контрольные материалы</h2>
        </div>
        <div class="col-8 right">
            <a href="#" class="btn white small" data-toggle="modal" data-target="#editCMModal"><i class="fa fa-check-square"></i> Редактировать</a>
        </div>
    </div>
    
    <!-- редактирование контр материалов -->
    <div class="modal fade" id="editCMModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
    				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-check-square"></i> Контрольные материалы</h4>
    			</div>
    			<div class="modal-body">
                    <div>
                        <i class="fa fa-plus-square-o"></i>
                        <a href="#" onclick="$('#editCourse-controlMaterialAddExist').slideToggle(); return false;">Добавить из существующих</a><br>
                        <i class="fa fa-plus-square"></i>
                        <a href="<?php echo $this->createUrl("/controlMaterial/create",array("idCourse" => $model->id, "isPoint" => false)); ?>">Создать тест</a><br>
                        <i class="fa fa-plus-square"></i>
                        <a href="<?php echo $this->createUrl("/controlMaterial/create",array("idCourse" => $model->id, "isPoint" => true)); ?>">Создать контрольную точку</a><br>
                    </div>
                    <div style = "display: none" id = "editCourse-controlMaterialAddExist" class="form modal-form">
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

    <hr>
    
    <div class="col-group">
        <div class="col-4">
            <h2>Учебные материалы</h2>
        </div>
        <div class="col-8 right">
            <a href="#" class="btn white small" data-toggle="modal" data-target="#editUMModal"><i class="fa fa-book"></i> Редактировать</a>
        </div>
    </div>
    
    
    <!-- редактирование уч материалов -->
    <div class="modal fade" id="editUMModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    	<div class="modal-dialog">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
    				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-book"></i> Контрольные материалы</h4>
    			</div>
    			<div class="modal-body">
                    <div>
                        <i class="fa fa-plus-square-o"></i>
                        <a href="#" onclick="$('#editCourse-uchMaterialAddExist').slideToggle(); return false;">Добавить из существующих</a>
                    </div>
    
                    <div style = "display: none" id = "editCourse-uchMaterialAddExist" class="form modal-form">
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
                                            /* $("#editCourse-uchMaterialAddExist").hide(); */
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
    
    			</div>
    		</div>
    	</div>
    </div>

    <div id = "editCourse-materials" style="margin-top:20px">
    </div>
</div>

</div>
<?php
$this->renderPartial("/site/bottom");
?>