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
$controlMaterials = CoursesControlMaterial::getAccessedControlMaterials($model->id);
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
                <div class="page-title">Курс: <?php echo $model->description?></div>
                <div class="page-subtitle">Преподаватели:
                    <div id = "editCourse-teachers" style="display: inline">

                    </div>
                    <div onclick="$('#editCourse-teacherSelect').show()"> Добавить преподавателя</div>
                    <div style = "display: none" id = "editCourse-teacherSelect">
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
                            // Options passed to the text input
                            'htmlOptions' => array('size' => 10),
                        ));

                        ?>
                    </div>
                </div>

                <div class="page-subtitle">Слушатели:
                    <div id = "editCourse-groups" style="display: inline">

                    </div>
                    <div onclick="$('#editCourse-groupSelect').show()"> Добавить слушателя</div>
                    <div style = "display: none" id = "editCourse-groupSelect">
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
                            // Options passed to the text input
                            'htmlOptions' => array('size' => 10),
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
                            // Options passed to the text input
                            'htmlOptions' => array('size' => 10),
                        ));
                        ?>
                        <div style="display: inline" onclick='addGroup($("#Group_Title").val(),$("#Term_title").val(),<?php echo $model->id; ?>);' >Добавить</div>

                    </div>
                </div>

            </div>


            <h2>Контрольные материалы</h2>
            <table class="table green">
                <thead>
                <tr>
                    <th>№</th>
                    <th width="40%" class="left">Название</th>
                    <th>Вопросов</th>
                    <th>Время</th>
                    <th>Попыток</th>
                    <th width="20%"></th>
                </tr>
                </thead>
                <tbody>
                <?php $num = 0;?>
                <?php foreach ($controlMaterials as $item):?>
                    <tr data-href = "<?php echo "/controlMaterial/startTest?idTest=".$item->id?>">
                        <?php $num++; ?>
                        <td class="center"><?php echo $num; ?></td>
                        <td><?php echo $item->title ?></td>
                        <?php
                        if ($item->question_show_count == -1) $showCount = count(Question::getQuestionsByControlMaterial($item->id)); else $showCount = $mat->question_show_count;
                        ?>
                        <td class="center"><?php echo $showCount == "" ? "—" : $showCount ?></td>
                        <td class="center"><?php echo $item->dotime == "" ? "—" : $item->dotime ?></td>
                        <?php
                        $tries = UserControlMaterial::model()->findAll('idUser = :idUser and idControlMaterial = :idControlMaterial', array(':idUser' => Yii::app()->user->getId(), ':idControlMaterial' => $item->id));
                        $countTries = count($tries);
                        ?>
                        <td class="center"><?php echo $countTries?> / <?= $item->try_amount == -1 ? '∞' : $item->try_amount ?></td>
                        <?php
                        $access = AccessControlMaterialGroup::model()->find('idControlMaterial = :idControlMaterial AND idGroup = NULL', array(':idControlMaterial' => $item->id));
                        if ($access == null)
                        {
                            $accessText = "Открыт";
                        } else
                        {
                            if ($access->access == 1) $accessText = "Открыт";
                            if ($access->access == 2) $accessText = "Открыт";
                            if ($access->access == 3)
                            {
                                $accessText = "Открыт";
                                if ($accessText->startDate != '0000-00-00 00:00:00')
                                    $accessText.= " с "+$accessText->startDate;
                                if ($accessText->endDate != '0000-00-00 00:00:00')
                                {
                                    if ($accessText->startDate != '0000-00-00 00:00:00')
                                        $accessText.= "<br>";
                                    $accessText.= "до "+$accessText->endDate;
                                }
                            }
                            if ($access->access == 4) $accessText = "После предыдущего";
                        }
                        ?>
                        <td class="right"><?php echo $accessText ?></td>
                    </tr>
                <?
                endforeach ?>
                </tbody>
            </table>

            </table>

            <h2>Учебные материалы</h2>
            <table class="table green">
                <thead>
                    <tr>
                        <th colspan="2" width="70%" class="left">Файл</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $learnMaterials = LearnMaterial::getMaterialsFromCourse($model->id);
                ?>
                <?php foreach ($learnMaterials as $item):?>
                    <? if($item->category != MATERIAL_TITLE) { ?>
                        <tr data-href="<?php echo $this->createUrl("/learnMaterial/getMaterial", array("matId" => $item->id)) ?>">
                    <? } else { ?>
                        <tr>
                    <? } ?>
                    
                        <?php if ($item->category == MATERIAL_TITLE):?>
                            <td class="title" colspan="2">
                                <!-- <i class="fileicon-file"></i> -->
                                <?= $item->title;?>
                            </td>
                        <? else: ?>
                            <td>
                                <?
                                    $path = "";
                                    if ($item->category==MATERIAL_FILE) {
                                        $path = pathinfo($item->path, PATHINFO_EXTENSION);
                                    }
                                    echo '<i class="fileicon-'.$path.'"></i>' . $item->title;
                                ?>
                            </td>
                            <td class="right">
                                <?php
                                $sizeText = "";
                                if ($item->category == MATERIAL_FILE)
                                {
                                    $size = filesize($item->getPathToMaterial());
                                    $sizePrefixxes = array(" Б"," Кб", " Мб", " Гб");
                                    $i = 0;
                                    do
                                    {
                                        $sizeText = $size.$sizePrefixxes[$i];
                                        $i++;
                                        $size = floor($size/1024);
                                    } while ($size>0);
                                }
                                echo $sizeText;
                                ?>

                            </td>
                        <? endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
<?php
$this->renderPartial("/site/bottom");
?>