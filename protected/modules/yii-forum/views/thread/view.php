<?php

$this->widget('zii.widgets.CBreadcrumbs', array('links'=>$thread->getBreadcrumbs()));

$header = '<div class="preheader"><div class="preheaderinner">'. CHtml::encode($thread->subject) .'</div></div>';
$footer = $thread->is_locked?'':'<div class="footer"><a href="/forum/thread/newreply?id='.$thread->id.'" class="btn small blue"><i class="fa fa-mail-reply"></i> Ответ</a></div>';
?>

<?php
    $this->widget('zii.widgets.CListView', array(
        //'htmlOptions'=>array('class'=>'thread-view'),
        'dataProvider'=>$postsProvider,
        'template'=>'{summary}{pager}'. $header .'{items}{pager}'. $footer,
        'itemView'=>'_post',
        'htmlOptions'=>array(
            'class'=>Yii::app()->controller->module->forumListviewClass,
        ),
    ));
