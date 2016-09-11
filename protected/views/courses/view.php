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
$learnMaterials = CoursesMaterial::getAccessedLearnMaterials($model->id);
?>
<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">Курс: <?php echo $model->title; ?><small><?php echo $model->description?></small></div>
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
                    <?php if (Yii::app()->user->isTeacher()):?>
                        <a href="<?php echo $this->createUrl("/courses/edit", array("id" => $model->id))?>" ><span>Перейти в режим редактирования</span></a>
                    <?php endif; ?>
                    <br>

                    <h2 id="files" class="col-9" style="padding-left:0 !important;margin-top:30px;">Учебные материалы</h2>
                    <span class="col-3 right">
                        <a href = "<?php echo $this->createUrl("courses/getCoursesFiles", array("idCourse" => $model->id));?>">
                            <i class="fa fa-download fa-2x has-tip" data-original-title="Скачать все материалы архивом" title="Скачать все материалы архивом"></i>
                        </a>
                    </span>
                    <table class="table green">
                        <thead>
                        <tr>
                            <th colspan="2" width="70%" class="left">Файл</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $visibleFolderStatus = true;
                        ?>
                        <?php foreach ($learnMaterials as $item):?>
                            <?php
                            $visibleStatus = ($item->accessInfo != null && $item->accessInfo->hasAccess);
                            if ($item->category == MATERIAL_TITLE)
                                $visibleFolderStatus = $visibleStatus;
                            if (!$visibleFolderStatus || !$visibleStatus)
                                continue;
                            ?>

                            <? if($item->category != MATERIAL_TITLE && $item->category != MATERIAL_WEBINAR) { ?>
                                <tr data-openInNewWindow = "<?php echo ($item->category != MATERIAL_INBROWSER) ? "1":"0" ?>" data-href="<?php echo $this->createUrl("/learnMaterial/getMaterial", array("matId" => $item->id)) ?>">
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
                                    <img class="file-icon" src="/img/fileicons/<?=$item->getIconExtension()?>.png" alt="">
                                    <?

                                    echo $item->getViewedTitle();
                                    ?>
                                </td>
                                <td class="right">
                                    <span class="short-link"><?php echo $item->getInfoText(); ?></span>
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
                            <tr
                                <?php if ($item->accessInfo != null && $item->accessInfo->hasAccess && !$item->is_point && $item->hasQuestions()): ?>
                                    data-href = "<?php echo "/controlMaterial/startTest?idTest=".$item->id?>"
                                <?php endif; ?>
                                >
                                <?php $num++; ?>
                                <td>
                                    <?php if ($item->is_point): ?>
                                        <img class="file-icon" src="/img/fileicons/point.png" alt="">
                                    <?php else:?>
                                        <img class="file-icon" src="/img/fileicons/test.png" alt="">
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
                                } else
                                {
                                    if ($item->get_files_from_students)
                                    {
                                        $this->renderPartial("/controlMaterial/userFileAnswerForm", array("idMaterial" => $item->id));
                                    }
                                    else
                                        echo "<td colspan='3'></td>";
                                    echo "<td class='center'>".ControlMaterial::getMark(Yii::app()->user->id,$item->id)."</td>";
                                }
                                ?>
                                <?php
                                $access = $item->accessInfo;
                                if ($access == null)
                                {
                                    $accessText = "Не нашел";
                                } else
                                {
                                    if ($access->accessType == 1) $accessText = "Открыт";
                                    if ($access->accessType == 2) $accessText = "Закрыт";
                                    if ($access->accessType == 3)
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
                                    if ($access->accessType == 4)
                                    {
                                        $parentTest = ControlMaterial::model()->findByPk($access->idBeforeMaterial);
                                        $accessText = "После прохождения<br>";
                                        $accessText.=$parentTest->title."<br>";
                                        $accessText.="Мин. оценка - ".$access->minMark;
                                    }
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
