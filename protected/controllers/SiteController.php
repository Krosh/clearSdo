<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{
    public $layout = "/layouts/main";

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

}