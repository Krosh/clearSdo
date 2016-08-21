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

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">Результаты поиска: <?php echo $query; ?></div>
                    </div>
                    <div>
                        <?php
                        $this->widget('zii.widgets.jui.CJuiTabs',array(
                            'tabs'=>array(
                                'Люди'=>array('content'=>$this->renderPartial("/site/search/peoples", array("users" => $users),true) , 'id'=>'peoplesTab'),
          /*                      'Сообщения на форуме'=>array('content'=>$this->renderPartial("/site/search/threads", array("threads" => $threads),true) , 'id'=>'threadsTab'),
          */                  ),
                            // additional javascript options for the tabs plugin
                            'options'=>array(
                                'collapsible'=>true,
                            ),
                            'id'=>'MyTab-Menu',
                        ));
                        ?>
                    </div>
                </div>
            </div>
