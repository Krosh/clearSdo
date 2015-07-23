<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array('Форум')
));

if(!Yii::app()->user->isGuest && Yii::app()->user->isAdmin)
{
    echo 'Админ: '. CHtml::link('Новая категория', array('/forum/forum/create')) .'<br />';
}

foreach($categories as $category)
{
    $this->renderpartial('_subforums', array(
        'forum'=>$category,
        'subforums'=>new CActiveDataProvider('Forum', array(
            'criteria'=>array(
                'scopes'=>array('forums'=>array($category->id)),
            ),
            'pagination'=>false,
        )),
    ));
}