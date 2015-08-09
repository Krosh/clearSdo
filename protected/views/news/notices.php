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
    $conferences = Conference::model()->findAll("idUser = ".Yii::app()->user->getId());
    foreach ($conferences as $conf)
    {
        $messages = Message::model()->findAll("idRecepient = ".$conf->idConference." AND isConference = 1 AND isPublishedOnMain = 1");
        foreach ($messages as $message)
        {
        ?>
            <div class="sidebar-content notice">
                <?php echo $message->text; ?>
            </div>
        <?php
        }
    }
    ?>

</div>
