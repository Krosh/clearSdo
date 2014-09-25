<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
?>
<?php
$this->renderPartial('top');
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
                                $config = Config::model()->findByPk(1);
                                $idActiveTerm = $config->idActiveTerm;
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
                            <a href="#" class="page-print"><i class="print"></i> <span>Версия для печати</span></a>
                        </div>
                    </div>

                    <div id = "ajaxCoursesDiv">

                    </div>
                    <script>
                        window.currentTerm = <?php echo $idActiveTerm?>
                    </script>

                </div>

            </div>
<?php
$this->renderPartial("/site/bottom");
?>