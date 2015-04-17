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
$teachers = Course::getAutors($model->id);
$controlMaterials = CoursesControlMaterial::getAccessedControlMaterials($model->id);
?>
<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading">
                <div class="page-title">Курс: <?php echo $model->description?></div>
                <div class="page-subtitle">Преподаватели:
                    <?php
                    $arTeachers = array();
                    foreach ($teachers as $teacher){
                        array_push($arTeachers, "<a href = '".$this->createUrl("/message/index", array("startDialog" => $teacher->id))."'>".$teacher->fio."</a>");
                    }

                    echo implode(', ', $arTeachers);
                    ?>
                </div>
            </div>

            <h2 id="files">Учебные материалы</h2>
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
                                
                                $f = "";
                                
                                switch ($path) {
                                    case "docx":
                                        $f = "file";
                                        break;
                                    case "txt":
                                        $f = "file";
                                        break;
                                    case "rtf":
                                        $f = "file";
                                        break;
                                    case "doc":
                                        $f = "file";
                                        break;
                                    case "pdf":
                                        $f = "pdf";
                                        break;
                                    case "xls":
                                        $f = "excel";
                                        break;
                                    case "xlsx":
                                        $f = "excel";
                                        break;
                                    case "csv":
                                        $f = "excel";
                                        break;
                                    case "ppt":
                                        $f = "presentation";
                                        break;
                                    case "pptx":
                                        $f = "presentation";
                                        break;
                                    case "zip":
                                        $f = "archive";
                                        break;
                                    case "rar":
                                        $f = "archive";
                                        break;
                                    case "7z":
                                        $f = "archive";
                                        break;
                                    case "tar":
                                        $f = "archive";
                                        break;
                                    case "gz":
                                        $f = "archive";
                                        break;
                                    case "jpg":
                                        $f = "image";
                                        break;
                                    case "jpeg":
                                        $f = "image";
                                        break;
                                    case "bmp":
                                        $f = "image";
                                        break;
                                    case "png":
                                        $f = "image";
                                        break;
                                    case "gif":
                                        $f = "image";
                                        break;
                                    case "avi":
                                        $f = "movie";
                                        break;
                                    case "mpg":
                                        $f = "movie";
                                        break;
                                    case "mp4":
                                        $f = "movie";
                                        break;
                                    case "mov":
                                        $f = "movie";
                                        break;
                                    case "torrent":
                                        $f = "torrent";
                                        break;
                                    default:
                                        $f = "no";
                                }
                                ?>
                                <img class="file-icon" src="/img/fileicons/<?=$f?>.png" alt="">
                                <?
                                
                                echo $item->getViewedTitle();
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


            <h2 id="learn">Контрольные материалы</h2>
            <table class="table green">
                <thead>
                <tr>
                    <th></th>
                <!--    <th>№</th>
                -->    <th width="40%" class="left">Название</th>
                    <th>Вопросов</th>
                    <th>Время</th>
                    <th>Попыток</th>
                    <th>Оценка</th>
                    <th width="20%"></th>
                </tr>
                </thead>
                <tbody>
                <?php $num = 0;?>
                <?php foreach ($controlMaterials as $item):?>
                    <?php
                        if ($item->access == 2) continue;
                    ?>
                    <tr
                        <?php if (ControlMaterial::hasAccess($item->id) && !$item->is_point): ?>
                            data-href = "<?php echo "/controlMaterial/startTest?idTest=".$item->id?>"
                        <?php endif; ?>
                        >
                        <?php $num++; ?>
                        <td>
                            <?php if ($item->is_point): ?>
                                <img src="/img/is_point.png" alt="">
                            <?php else:?>
                                <img src="/img/is_test.gif" alt="">
                            <?php endif; ?>
                        <td>
                        <?php echo $item->title ?></td>
                        <?php
                        if (!$item->is_point)
                        {
                        if ($item->question_show_count == -1) $showCount = count(Question::getQuestionsByControlMaterial($item->id)); else $showCount = $mat->question_show_count;
                        ?>
                        <td class="center"><?php echo $showCount == "" ? "—" : $showCount ?></td>
                        <td class="center"><?php echo $item->dotime == "" ? "—" : $item->dotime ?></td>
                        <?php
                        $tries = UserControlMaterial::model()->findAll('idUser = :idUser and idControlMaterial = :idControlMaterial', array(':idUser' => Yii::app()->user->getId(), ':idControlMaterial' => $item->id));
                        $countTries = count($tries);
                        ?>
                        <td class="center"><?php echo $countTries?> / <?= $item->try_amount == -1 ? '∞' : $item->try_amount ?></td>
                        <td class="center">
                            <?php echo ControlMaterial::getMark(Yii::app()->user->getId(), $item->id); ?>
                        </td>
                        <?php
                        $access = AccessControlMaterialGroup::model()->find('idControlMaterial = :idControlMaterial', array(':idControlMaterial' => $item->id));
                        if ($access == null)
                        {
                            $accessText = "Открыт";
                        } else
                        {
                            if ($access->access == 1) $accessText = "Открыт";
                            if ($access->access == 2) $accessText = "Закрыт";
                            if ($access->access == 3)
                            {
                                $accessText = "Открыт<br>";
                                if ($accessText->startDate != '0000-00-00 00:00:00')
                                    $accessText.= " с ".$access->startDate;
                                if ($accessText->endDate != '0000-00-00 00:00:00')
                                {
                                    if ($accessText->startDate != '0000-00-00 00:00:00')
                                        $accessText.= "<br>";
                                    $accessText.= "до ".$access->endDate;
                                }
                            }
                            if ($access->access == 4)
                            {
                                $parentTest = ControlMaterial::model()->findByPk($access->idBeforeTest);
                                $accessText = "После прохождения<br>";
                                $accessText.=$parentTest->title."<br>";
                                $accessText.="Мин. оценка - ".$access->minMark;
                            }
                        }
                        } else
                        {
                            if ($item->get_files_from_students)
                            {
                                $this->renderPartial("/controlMaterial/userFileAnswerForm", array("idMaterial" => $item->id));
                            }
                            else
                                echo "<td colspan='3'></td>";
                            echo "<td class='center'>".ControlMaterial::getMark(Yii::app()->user->id,$item->id)."</td>";
                            $accessText = "";
                        }
                        ?>
                        <td class="right"><?php echo $accessText ?></td>
                    </tr>
                <?
                endforeach ?>
                </tbody>
            </table>
        </div>

    </div>
