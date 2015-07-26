<?php
// For admins, add link to delete post
$isAdmin = !Yii::app()->user->isGuest && Yii::app()->user->isAdminOnForum();
?>
<div class="post">
    <div class="header">
        <?php echo Yii::app()->dateFormatter->format("dd MMM yyyy, HH:mm", $data->created); ?> от <?php echo CHtml::link(CHtml::encode($data->author->name), $data->author->url); ?>
        <?php if($data->editor) echo ' (Отредактировано: '. Yii::app()->controller->module->format_date($data->updated, 'long') .' пользователем '. CHtml::link(CHtml::encode($data->editor->name), $data->editor->url) .')'; ?>

        <div class="admin" style="float:right; border:none;vertical-align: middle; vertical-align: top;">
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
            <?if($isAdmin || Yii::app()->user->id == $data->author_id): ?>
                <a href="/forum/post/update?id=<?=$data->id?>"><i class="fa fa-pencil"></i></a>
            <?endif; ?>
        </div>
    </div>
    <div class="content">
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
    </div>
</div>
