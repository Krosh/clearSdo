<?php

class ResourcesController extends CController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/full';
    public $noNeedJquery = false;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.AccessFilter'),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
        );
    }


    public function actionLearnMaterials()
    {
        $this->noNeedJquery = true;
        $model=new LearnMaterial('search');
        $model->unsetAttributes();  // clear any default values
        if (Yii::app()->user->getState('mediaSearchParams') == null)
        {
            Yii::app()->user->setState('mediaSearchParams', array());
        }
        if(isset($_GET['LearnMaterial']))
        {
            Yii::app()->user->setState('mediaSearchParams', array_merge(Yii::app()->user->getState('mediaSearchParams'),$_GET['LearnMaterial']));
            $model->attributes=Yii::app()->user->getState('mediaSearchParams');
        }
        else
        {
            $searchParams = Yii::app()->user->getState('mediaSearchParams');
            if (isset($searchParams))
            {
                $model->attributes = $searchParams;
            }
        }

        $this->render('learnMaterials',array(
            'model'=>$model,
        ));
    }

    public function actionCourses()
    {
        $this->noNeedJquery = true;
        $model=new Course('search');
        $model->unsetAttributes();  // clear any default values
        if (Yii::app()->user->getState('courseSearchParams') == null)
        {
            Yii::app()->user->setState('courseSearchParams', array());
        }
        if(isset($_GET['Course']))
        {
            Yii::app()->user->setState('courseSearchParams', array_merge(Yii::app()->user->getState('courseSearchParams'),$_GET['Course']));
            $model->attributes=Yii::app()->user->getState('courseSearchParams');
        }
        else
        {
            $searchParams = Yii::app()->user->getState('courseSearchParams');
            if (isset($searchParams))
            {
                $model->attributes = $searchParams;
            }
        }

        $this->render('course',array(
            'model'=>$model,
        ));
    }

    public function actionControlMaterials()
    {
        $this->noNeedJquery = true;
        $model=new ControlMaterial('search');
        $model->unsetAttributes();  // clear any default values
        if (Yii::app()->user->getState('controlSearchParams') == null)
        {
            Yii::app()->user->setState('controlSearchParams', array());
        }
        if(isset($_GET['ControlMaterial']))
        {
            Yii::app()->user->setState('controlSearchParams', array_merge(Yii::app()->user->getState('controlSearchParams'),$_GET['ControlMaterial']));
            $model->attributes=Yii::app()->user->getState('controlSearchParams');
        }
        else
        {
            $searchParams = Yii::app()->user->getState('controlSearchParams');
            if (isset($searchParams))
            {
                $model->attributes = $searchParams;
            }
        }

        $this->render('controlMaterials',array(
            'model'=>$model,
        ));
    }

}
