<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{
    public $layout = "/layouts/main";
    public $noNeedJquery = false;
    public $breadcrumbs;


    public function actions()
    {
        return array(
            'upload'=>array(
                'class'=>'xupload.actions.XUploadAction',
                'path' =>Yii::app() -> getBasePath() . "/../uploads",
                'publicPath' => Yii::app() -> getBaseUrl() . "/uploads",
            ),
        );
    }

    public function filters()
    {
        return array(
            array('application.filters.AccessFilter'),
            array('application.filters.ActiveTestFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect('/');
    }

    public function actionIndex()
    {
        // Проверка авторизации
        $auth = false;
        $model = new User();
        if(Yii::app()->user->isGuest)
        {
            if (isset($_POST['User']))
            {
                $model->attributes = $_POST['User'];
                $identity=new UserIdentity($model->login,$model->password);
                if($identity->authenticate())
                {
                    Yii::app()->user->setFlash('message','Авторизация прошла успешно');
                    Yii::app()->user->login($identity);
                    $auth = true;
                    $this->redirect('/');
                }
                else
                {
                    $auth = false;
                }
            }
        }
        else
        {
            $auth = true;

        }
        if ($auth)
            $this->render("mainPage");
        else
            $this->render("loginForm",array('model' => $model));
    }

    public function actionViewCourse($idCourse)
    {

        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            // Бросить ошибку
        }
        $this->render('viewCourse', array('model' => $course));
    }

    public function actionEditCourse($idCourse)
    {
        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }
        Yii::app()->session['currentCourse'] = $idCourse;
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => $idCourse)))
        );
        if(isset($_POST['Course']))
        {
            $course->attributes=$_POST['Course'];
            if($course->save())
                $this->refresh();
        }

        $this->noNeedJquery = true;
        $this->render('editCourse', array('model' => $course));
    }

    public function actionJournal($idCourse,$idGroup)
    {
        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }
        $group = Group::model()->findByPk($idGroup);
        if ($group == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }

        if(Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial("/journal/table", array("idCourse" => $idCourse, "group" => $group));
        }
        else
        {
            $this->breadcrumbs=array(
                $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => $idCourse))),
                "Журнал ".$group->Title => array($this->createUrl("/site/journal",array("idCourse" => $idCourse, "idGroup" => $idGroup)))
            );
            $this->render("/journal/view", array("course" => $course, "group" => $group));
        }
    }

    public function actionConfig()
    {
        $config = Config::model()->findByPk(1);
        $this->render('config',array("config" => $config));
    }

    public function actionNoAccess()
    {
        $this->render("noAccess");
    }

    public function actionMediateka()
    {
        $this->noNeedJquery = true;
        $this->render("mediateka");
    }

    public function actionDeleteMedia($name)
    {
        $path = Yii::getPathOfAlias("webroot.media.".Yii::app()->user->id).DIRECTORY_SEPARATOR.$name;
        if (is_file($path))
            unlink($path);
    }


}