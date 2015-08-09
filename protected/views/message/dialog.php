<?php foreach ($messages as $item):?>
<div class="message <?php if ($item->status == 0) echo "unread"?>">
    <?php
    if ($item->idAutor == Yii::app()->user->getId())
        $autor = Yii::app()->user->getModel();
    else
    {
        if ($isConference)
            $autor = User::model()->findByPk($item->idAutor);
        else
            $autor = $user;
    }
    ?>
    <?php if ($item->isPublishedOnMain):?>
        <div class="row">
            <p class="text" style="background-color: #f1c30f"><?php echo $item->text?></p>
        </div>
    <?php elseif ($item->isService):?>
        <div class="row">
            <p class="text"><?php echo $item->text?></p>
        </div>
    <?php else: ?>
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
            <?php if (Yii::app()->user->isAdmin() && $item->idAutor == Yii::app()->user->getId()): ?>
                <div class="right">
                    <a href="#" onclick="deleteMessage(<?php echo $item->id; ?>); return false"><i class="fa fa-remove"></i></a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
    <?php
    // TODO :: Вынести это в хотя бы в контроллер
    if ($item->idRecepient == Yii::app()->user->id && $item->status == 0)
    {
        $item->status = 1;
        $item->save();
    }
    ?>
<?php endforeach; ?>
