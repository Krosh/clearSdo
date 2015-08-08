<?php

$isAdmin = !Yii::app()->user->isGuest && Yii::app()->user->isAdminOnForum($forum->id);

$gridColumns = array(
    array(
        'name' => 'Категория',
        'headerHtmlOptions' => array('colspan' => '2'),
        'type' => 'html',
        'value' => "",
        'htmlOptions' => array('style' => 'width:22px;'),
    ),
    array(
        'name' => 'Категория',
        'headerHtmlOptions' => array('style' => 'display:none'),
        'type' => 'html',
        'value' => '$data->renderForumCell()',
    ),
    array(
        'name' => 'threadCount',
        'headerHtmlOptions' => array('style' => 'text-align:center;'),
        'header' => 'Темы',
        'htmlOptions' => array('style' => 'width:65px; text-align:center;'),
    ),
    array(
        'name' => 'postCount',
        'headerHtmlOptions' => array('style' => 'text-align:center;'),
        'header' => 'Сообщения',
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

if(isset($inforum) && $inforum == true)
    $preheader = '<div>Подкатегории в "' . CHtml::encode($forum->title) . '"</div>';
else
    $preheader = CHtml::link(CHtml::encode($forum->title), $forum->url);

// Add some admin controls
if($isAdmin)
{
    $deleteConfirm = "Вы уверены? Все темы и записи будут удалены!";

    $adminheader =
        '<div class="admin" style="float:right; font-size:smaller;">'.
            CHtml::link('Новая категория', array('/forum/create', 'parentid'=>$forum->id)) .' | '.
            CHtml::link('Редактировать', array('/forum/update', 'id'=>$forum->id)) .' | '.
            CHtml::ajaxLink('Удалить категорию',
                array('/forum/delete', 'id'=>$forum->id),
                array('type'=>'POST', 'success'=>'function(){document.location.reload(true);}'),
                array('confirm'=>$deleteConfirm)
            ).
        '</div>';

    $preheader = $adminheader . $preheader;

    // Admin links to show in extra column
    $gridColumns[] = array(
        'class'=>'CButtonColumn',
        'header'=>'Админ',
        'template'=>'{update} {delete}',
        'deleteConfirmation'=>"js:'".$deleteConfirm."'",
        'afterDelete'=>'function(){document.location.reload(true);}',
        'buttons'=>array(
            'delete'=>array(
                'url'=>'Yii::app()->createUrl("/forum/delete", array("id"=>$data->id))',
                'label' => '<i class="fa fa-remove"></i>',
                'imageUrl' => false,
                'options'=>array('title'=>''),
            ),
            'update'=>array(
                'url'=>'Yii::app()->createUrl("/forum/update", array("id"=>$data->id))',
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
    'summaryText' => '',
    'selectableRows' => 0,
    'emptyText' => 'Не найдено категорий',
    'showTableOnEmpty'=>$isAdmin,
    'preHeader'=>$preheader,
    'preHeaderHtmlOptions' => array(
        'class' => 'preheader',
    ),
    'dataProvider'=>$subforums,
    'columns' => $gridColumns,
    'htmlOptions'=>array(
        'class'=>Yii::app()->controller->module->forumTableClass,
    )
));
