<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 09.08.15
 * Time: 14:29
 * To change this template use File | Settings | File Templates.
 */?>
<div class="sidebar-item">
    <div class="sidebar-title">
        Объявления
    </div>
    <?php
    $readedNotices = ReadedNotice::model()->findAll("idUser = ".Yii::app()->user->getId()." AND isReaded = 0");
    ?>
    <?php foreach ($readedNotices as $notice):?>
        <?php
        $message = $notice->message;
        ?>
        <div class="sidebar-content notice" style="padding-bottom: 20px">
            <div class="col-10" style="padding-bottom: 5px">
                <?php echo $message->text; ?>
            </div>
            <div class="col-1">
                <a href = "#" onclick="readNotice(<?php echo $notice->id; ?>,$(this).parent().parent()); return false"><i class="fa fa-remove"></i></a>
            </div>
        </div>
    <?php endforeach; ?>

</div>
