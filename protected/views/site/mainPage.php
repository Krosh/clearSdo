<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
?>


<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading col-group">
                        <div class="col-6">
                            <div class="page-title">Текущие курсы</div>

                            <div class="courses-list dropdown nohover">

                                <?php
                                if (!isset(Yii::app()->session['currentTerm']))
                                {
                                    $config = Config::model()->findByPk(1);
                                    Yii::app()->session['currentTerm'] = $config->idActiveTerm;
                                }
                                $idActiveTerm = Yii::app()->session['currentTerm'];
                                $activeTerm = Term::model()->findByPk($idActiveTerm);
                                echo '(<a href="#" class="caret-link">
                                    <span id = "currentTermTitle">'.$activeTerm->title.' </span><i class="caret"></i>
                                </a>)';

                                ?>

                                <div class="dropdown-container">
                                    <?php
                                        $terms = Term::model()->findAll('id >0',array(':idTerm' => $idActiveTerm));
                                        foreach ($terms as $item)
                                        {
                                            echo '<a href="#" onclick="loadCourses('.$item->id.',\''.$item->title.'\');return false;">'.$item->title.'</a>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 right">
                            <i class="fa fa-refresh fa-spin fa-loading-icon"></i>
                            <a target="_blank" href="#" class="page-print"><i class="print"></i> <span>Версия для печати</span></a>
                        </div>
                    </div>
                    
                    <?php if(Yii::app()->user->isTeacher()):?>
                        <a id = "addCourse" class="btn white small" href="/courses/create"><i class="fa fa-plus"></i> Создать новый курс</a>
                    <?php endif; ?>
                    <div id = "ajaxCoursesDiv">

                    </div>
                    <?php if(Yii::app()->user->isTeacher()):?>
                        <a class="btn white small" href="/courses/create"><i class="fa fa-plus"></i> Создать новый курс</a>
                    <?php endif; ?>
                    <script>
                        window.currentTerm = <?php echo $idActiveTerm?>
                    </script>

                </div>

            </div>
