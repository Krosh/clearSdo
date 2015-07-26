<?php
$this->widget('zii.widgets.CBreadcrumbs', array('links'=>array(
    'Форум'=>array('/forum'),
    $user->name
)));

$siglink = (!Yii::app()->user->isGuest && (Yii::app()->user->isAdminOnForum() || Yii::app()->user->forumuser_id == $user->id))?' ['. CHtml::link('Edit', array('user/update', 'id'=>$user->id)) .']':'';

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$user,
    'attributes'=>array(
        'name',
        array(
            'label'=>'Дата регистрации',
            'value'=>Yii::app()->controller->module->format_date($user->firstseen),
        ),
        array(
            'label'=>'Последний вход',
            'value'=>Yii::app()->controller->module->format_date($user->lastseen),
        ),
        'postCount',
        array(
            'label'=>'Профиль',
            'type'=>'html',
            'value'=>isset(Yii::app()->controller->module->userUrl)?CHtml::link('Details', $this->evaluateExpression(Yii::app()->controller->module->userUrl, array('id'=>$user->siteid))):'n/a',
        ),
        array(
            'name'=>'signature',
            'label'=>'Подпись'. $siglink,
            // 'type'=>'html',
        ),
    ),
    'htmlOptions'=>array(
        'class'=>Yii::app()->controller->module->forumDetailClass,
    )
));
