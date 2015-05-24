<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{
    public $layout = "/layouts/full";
    public $noNeedJquery = false;
    public $breadcrumbs;


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
                    $this->redirect('/site/index');
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
        {
            $this->layout = "/layouts/main";
            $this->render("loginForm",array('model' => $model));
        }
    }

    public function actionViewCourse($idCourse)
    {
        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            // Бросить ошибку
        }
        Yii::app()->session['currentCourse'] = $idCourse;
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
        $isSaved = false;
        $config = Config::model()->findByPk(1);
        if(isset($_POST['Config']))
        {
            $config->attributes=$_POST['Config'];
            if($config->save())
                $isSaved = true;
        }
        $this->render('config',array("config" => $config, "isSaved" => $isSaved));
    }

    public function actionNoAccess()
    {
        $this->render("noAccess");
    }


    public function actionPlugin($id)
    {
        $this->noNeedJquery = true;
        $params = $this->getActionParams();
        unset($params["id"]);
        if (Yii::app()->request->isAjaxRequest)
        {
            PluginController::$plugins[$id]->render(array_merge($params,$_POST),$this);
        }
        else
            $this->render("plugins", array("plugin" => PluginController::$plugins[$id], "params" => $params));
    }


    public function actionUserConfig()
    {
        $model=User::model()->findByPk(Yii::app()->user->getId());

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $changeAccepted = false;
        if(isset($_POST['User']))
        {
            $flag = true;
            $model->attributes=$_POST['User'];
            if ($_POST['haveNewPassword'])
            {
                if ($model->password == md5($_POST["oldPassword"]))
                {
                    if ($_POST["newPassword"] == $_POST["confirmNewPassword"])
                    {
                        $model->password = md5($_POST["newPassword"]);
                        Yii::app()->user->setFlash("codeMessage","success");
                        Yii::app()->user->setFlash("message","Пароль изменен");
                        $flag = false;
                    } else
                    {
                        Yii::app()->user->setFlash("codeMessage","error");
                        Yii::app()->user->setFlash("message","Введенные пароли не совпадают");
                        $flag = false;
                    }

                } else
                {
                    Yii::app()->user->setFlash("codeMessage","error");
                    Yii::app()->user->setFlash("message","Старый пароль введен неверно");
                    $flag = false;
                }
            }
            $model->newAvatar = $_POST['User']['newAvatar'];
            $model->save();

        }

        $this->render('userConfig/update',array(
            'model'=>$model,
        ));
    }

    public function actionWebinar()
    {
        $this->render('/webinar/view',array(
        ));
    }

    public function actionProfile($idUser)
    {
        $user = User::model()->findByPk($idUser);
        if ($user == null || $user->role != ROLE_TEACHER)
            throw new CHttpException(404,"");
        $this->render('/site/profile', array('model' => $user));

    }

    public function actionSearch($query)
    {
        // Поиск по людям
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('fio',$query);
        $q = ROLE_TEACHER;
        $criteria->compare("role",1);
        $users = User::model()->findAll($criteria);


        $this->render('/site/search', array('query' => $query, 'users' => $users));
    }



}