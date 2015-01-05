<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 23.09.14
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$this->renderPartial('/site/top');
?>


    <div class="wrapper">
        <div class="container">
            <div class="col-group">
                <div class="col-9">

                    <div class="content">
                        <div class="page-heading col-group">
                            <div class="col-12">
                                <div class="page-title">Отчет об успеваемости</div>
                                <form>
                                    <?php
                                        $currentTerm = Yii::app()->session['currentTerm'];
                                    ?>
                                    <table width="100%" style="margin: 10px 0;">
                                        <tr>
                                            <td style="padding-bottom: 10px;" width="20%">Группа:</td>
                                            <td>
                                                <?php
                                                $groups = Group::getGroupsByAutor(Yii::app()->user->getId());
                                                $items = array();
                                                foreach ($groups as $item)
                                                {
                                                    $items[$item->id] = $item->Title;
                                                }
                                                $this->widget('ext.combobox.EJuiComboBox', array(
                                                    'name' => 'group',
                                                    'data' => $items,
                                                    'options' => array(
                                                        'allowText' => false
                                                    ),
                                                    'htmlOptions' => array('size' => 30),
                                                ));
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="20%">Курс:</td>
                                            <td>
                                                <?php
                                                $courses = Course::getCoursesByAutor(Yii::app()->user->getId(),$currentTerm);
                                                $items = array();
                                                foreach ($courses as $item)
                                                {
                                                    $items[$item->id] = $item->title;
                                                }
                                                $this->widget('ext.combobox.EJuiComboBox', array(
                                                    'name' => 'course',
                                                    'data' => $items,
                                                    'value' => $_GET['course'],
                                                    'options' => array(
                                                        'allowText' => false
                                                    ),
                                                    'htmlOptions' => array('size' => 30),
                                                ));
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="button" class="btn blue small" value = "Построить отчет" onclick="makeReport_marks()">
                                </form>
                                <div id = "report">
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php
$this->renderPartial("/site/bottom");
?>