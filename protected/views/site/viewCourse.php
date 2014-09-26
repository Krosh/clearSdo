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
    $autors = CoursesAutor::model()->findAll('idCourse = '.$model->id);
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
                array_push($arTeachers, "<a href = '#'>".$teacher->fio."</a>");
            }
            
            echo implode(', ', $arTeachers);
            ?>
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
                    <th width="20%">Дата</th>
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
                        <td class="center"><?php echo $showCount ?></td>
                        <td class="center"><?php echo $item->dotime?></td>
                        <?php
                            $tries = UserControlMaterial::model()->findAll('idUser = :idUser and idControlMaterial = :idControlMaterial', array(':idUser' => Yii::app()->user->getId(), ':idControlMaterial' => $item->id));
                            $countTries = count($tries);
                        ?>
                        <td class="center"><?php echo $countTries?>/<?php echo ($item->try_amount == -1?'Неограниченно':$item->try_amount)?></td>
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
        		    <th>№</th>
        		    <th width="40%" class="left">Название</th>
        		    <th>Расширение</th>
        		    <th>Размер, КБ</th>
        		    <th></th>
    		    </tr>
		    </thead>
    		<tbody>
        		<tr>
            		<td class="center">1</td>
            		<td>ВССиТ(2) Вопросы к экзамену</td>
            		<td class="center">.doc</td>
            		<td class="center">15</td>
            		<td class="right"><a href="#">Скачать</a></td>
        		</tr>
        		<tr>
            		<td class="title" colspan="5">
            		    Лабораторные работы
            		</td>
        		</tr>
        		<tr>
            		<td class="center">1</td>
            		<td>ВССиТ(2) Вопросы к экзамену</td>
            		<td class="center">.doc</td>
            		<td class="center">15</td>
            		<td class="right"><a href="#">Скачать</a></td>
        		</tr>
        		<tr>
            		<td class="center">1</td>
            		<td>ВССиТ(2) Вопросы к экзамену</td>
            		<td class="center">.doc</td>
            		<td class="center">15</td>
            		<td class="right"><a href="#">Скачать</a></td>
        		</tr>
        		<tr>
            		<td class="center">1</td>
            		<td>ВССиТ(2) Вопросы к экзамену</td>
            		<td class="center">.doc</td>
            		<td class="center">15</td>
            		<td class="right"><a href="#">Скачать</a></td>
        		</tr>
        		<tr>
            		<td class="center">1</td>
            		<td>ВССиТ(2) Вопросы к экзамену</td>
            		<td class="center">.doc</td>
            		<td class="center">15</td>
            		<td class="right"><a href="#">Скачать</a></td>
        		</tr>
    		</tbody>
		</table>
		
    </div>

</div>
<?php
$this->renderPartial("/site/bottom");
?>