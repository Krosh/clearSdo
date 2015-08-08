<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>$forum->getBreadcrumbs(),
));

$this->renderPartial('_subforums', array(
    'inforum'=>true,
    'forum' => $forum,
    'subforums' => $subforumsProvider,
));

$newthread = $forum->is_locked?'':'<div class="newthread" style="float:right;"><a href="/forum/thread/create?id='.$forum->id.'" class="btn small blue"><i class="fa fa-plus-square"></i> Новая тема</a></div>';

$gridColumns = array(
    array(
        'name' => 'Тема',
        'headerHtmlOptions' => array('colspan' => '2'),
        'type' => 'html',
        'value' => '$data->is_locked ? "<i class=\"fa fa-file\"></i>" : "<i class=\"fa fa-file-o\"></i>"',
        'htmlOptions' => array('style' => 'width:20px;'),
    ),
    array(
        'name' => 'Тема',
        'headerHtmlOptions' => array('style' => 'display:none'),
        'type' => 'html',
        'value' =>'$data->renderSubjectCell()',
    ),
    array(
        'name' => 'postCount',
        'header' => 'Ответы',
        'headerHtmlOptions' => array('style' => 'text-align:center;'),
        'htmlOptions' => array('style' => 'width:65px; text-align:center;'),
    ),
    array(
        'name' => 'view_count',
        'header' => 'Сообщения',
        'headerHtmlOptions' => array('style' => 'text-align:center;'),
        'htmlOptions' => array('style' => 'width:65px; text-align:center;'),
    ),
    array(
        'name' => 'Последнее сообщение',
        'headerHtmlOptions' => array('style' => 'text-align:center;'),
        'type' => 'html',
        'value' => '$data->renderLastpostCell()',
        'htmlOptions' => array('style' => 'width:200px; text-align:right;'),
    ),
);

// For admins, add column to delete and lock/unlock threads
$isAdmin = !Yii::app()->user->isGuest && Yii::app()->user->isAdminOnForum($forum->id);
if($isAdmin)
{
    // Admin links to show in extra column
    $deleteConfirm = "Вы уверены? Все посты будут удалены!";
    $gridColumns[] = array(
        'class'=>'CButtonColumn',
        'header'=>'Админ',
        'template'=>'{update} {delete}',
        'deleteConfirmation'=>"js:'".$deleteConfirm."'",
        'afterDelete'=>'function(){document.location.reload(true);}',
        'buttons'=>array(
            'delete'=>array(
                'url'=>'Yii::app()->createUrl("/forum/thread/delete", array("id"=>$data->id))',
                'label' => '<i class="fa fa-remove"></i>',
                'imageUrl' => false,
                'options'=>array('title'=>''),
            ),
            'update'=>array(
                'url'=>'Yii::app()->createUrl("/forum/thread/update", array("id"=>$data->id))',
                'label' => '<i class="fa fa-pencil"></i>',
                'imageUrl' => false,
                'options'=>array('title'=>''),
            ),
        ),
        'htmlOptions' => array('style' => 'width:40px; text-align:center;'),
    );
}

$this->widget('forum.extensions.groupgridview.GroupGridView', array(
    'enableSorting' => false,
    'selectableRows' => 0,
    // 'emptyText'=>'', // No threads? Show nothing
    // 'showTableOnEmpty'=>false,
    'preHeader' => CHtml::encode($forum->title),
    'preHeaderHtmlOptions' => array(
        'class' => 'preheader',
    ),
    'dataProvider' => $threadsProvider,
    'template'=>'{summary}'. $newthread .'{pager}{items}{pager}'. $newthread,
    'extraRowColumns' => array('is_sticky'),
    'extraRowExpression' => '"<b>".($data->is_sticky?"Закрепленные темы":"Обычные темы")."</b>"',
    'columns' => $gridColumns,
    'htmlOptions'=>array(
        'class'=>Yii::app()->controller->module->forumTableClass,
    )
));

