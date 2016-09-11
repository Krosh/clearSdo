<?php

/**
 * SiteController is the default controller to handle user requests.
 */

define("CACHE_NEWS","CACHE_NEWS");

class NewsController extends CController
{
    public function filters()
    {
        return array(
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter')
        );
    }

    public function actionNews()
    {
        $text = Yii::app()->cache->get(CACHE_NEWS);
        if ($text === false)
        {
            $text = $this->renderPartial('ajax',array(),true);
            Yii::app()->cache->set(CACHE_NEWS,$text,60*60);
        }
        echo $text;
    }

}