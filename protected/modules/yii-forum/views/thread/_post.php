<?php
// For admins, add link to delete post
$isAdmin = !Yii::app()->user->isGuest && Yii::app()->user->isAdminOnForum($data->thread->forum->id);
?>
<div class="post">
    <div class="header">
        <?php echo Yii::app()->dateFormatter->format("dd MMM yyyy, HH:mm", $data->created); ?>
        <?php if($data->editor) echo ' (Отредактировано: '. Yii::app()->controller->module->format_date($data->updated, 'long') .' пользователем '. CHtml::link(CHtml::encode($data->editor->name), $data->editor->url) .')'; ?>

        <div class="admin" style="float:right; border:none;vertical-align: middle; vertical-align: top;">
            <?if($isAdmin || Yii::app()->user->id == $data->author_id): ?>
                <a href="/forum/post/update?id=<?=$data->id?>"><i class="fa fa-pencil"></i></a>
            <?endif; ?>
            <? if($isAdmin)
                {
                    $deleteConfirm = "Вы уверены? Этот ответ будет удален!";
                    echo CHtml::ajaxLink('<i class="fa fa-remove"></i>',
                        array('/forum/admin/deletepost', 'id'=>$data->id),
                        array('type'=>'POST', 'success'=>'function(){document.location.reload(true);}'),
                        array('confirm'=>$deleteConfirm, 'id'=>'post'.$data->id)
                    );
                }
            ?>
        </div>
    </div>
    <div class="content">
        <table class="post-answer-view">
            <tr>
                <td>
                    <div class="the-avatar-box" style="background-image: url('<?=($data->author->sdoUser->getAvatarPath(AVATAR_SIZE_MEDIUM));?>')"></div>
                    <?php echo CHtml::link(CHtml::encode($data->author->name), $data->author->url); ?>

                    <div class="fut" style="border:none!important;">
                        <?=CHtml::link('<i class="fa fa-user"></i>', $data->author->url, array('target'=>'_blank','class'=>'btn tiny blue has-tip','title'=>'Профиль пользователя')); ?>
                        <?=CHtml::link('<i class="fa fa-envelope"></i>', array('/message/index','startDialog'=>$data->author->sdoUser->id), array('target'=>'_blank','class'=>'btn tiny blue has-tip','title'=>'Написать сообщение')); ?>
                    </div>
                </td>
                <td>
                    <?php
                        $this->beginWidget('CMarkdown', array('purifyOutput'=>true));
                            echo $data->content;
                        $this->endWidget();

                        if($data->author->signature)
                        {
                            echo '<br />---<br />';
                            $this->beginWidget('CMarkdown', array('purifyOutput'=>true));
                                echo $data->author->signature;
                            $this->endWidget();
                        }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>
