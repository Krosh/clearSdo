<?php foreach ($messages as $item):?>
    <div class="message <?php if ($item->status == 0) echo "unread"?>">
    <?php
        if ($item->idAutor == Yii::app()->user->getId())
            $autor = Yii::app()->user->getModel();
        else
            $autor = $user;
    ?>
    <div class="row">
        <div class="col-xs-1">
            <a href="#" class="avatar" style="background-image: url(<?php echo $autor->getAvatarPath(); ?>)"></a>
        </div>
        <div class="col-xs-11">
            <a href="#" class="link"><?php echo $autor->getShortFio(); ?></a> <span class="date">
                <?php echo DateHelper::getDifference($item->dateSend,date("Y-m-d H:i:s")); ?>
            </span>
            <p class="text"><?php echo $item->text?></p>
        </div>
    </div>
</div>
    <?php
        if ($item->idRecepient == Yii::app()->user->id && $item->status == 0)
        {
            $item->status = 1;
            $item->save();
        }
    ?>
<?php endforeach; ?>
