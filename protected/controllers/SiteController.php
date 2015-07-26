<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{
    public $layout = "/layouts/full";
    public $breadcrumbs;


    private function runMigrationTool() {
        $runner=new CConsoleCommandRunner();
        $runner->commands=array(
            'migrate' => array(
                'class' => 'system.cli.commands.MigrateCommand',
                'interactive' => false,
            ),
        );

        ob_start();
        $runner->run(array(
            'yiic',
            'migrate',
            //          'down'
//                      'create',
  //                  'add_links_to_forumusers',
        ));
        echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    public function actionMigration()
    {
        $this->runMigrationTool();
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

        $this->render('editCourse', array('model' => $course));
    }

    public function actionJournal($idCourse,$idGroup, $print = false)
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
            if ($print)
            {
                $this->layout = "";
                $this->render("/journal/table", array("idCourse" => $course->id, "group" => $group, "print" => true));
            } else
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
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if ($_POST['haveNewPassword'])
            {
                if ($model->password == md5($_POST["oldPassword"]))
                {
                    if ($_POST["newPassword"] == $_POST["confirmNewPassword"])
                    {
                        $model->password = md5($_POST["newPassword"]);
                        $model->dateChangePassword = date("Y-m-d H:i:s");
                        Yii::app()->user->setFlash("codeMessage","success");
                        Yii::app()->user->setFlash("message","Пароль изменен");
                    } else
                    {
                        Yii::app()->user->setFlash("codeMessage","error");
                        Yii::app()->user->setFlash("message","Введенные пароли не совпадают");
                    }
                } else
                {
                    Yii::app()->user->setFlash("codeMessage","error");
                    Yii::app()->user->setFlash("message","Старый пароль введен неверно");
                }
            }
            $model->newAvatar = $_POST['User']['newAvatar'];
            $model->save();
        }

        $this->render('userConfig/update',array(
            'model'=>$model,
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
        $users = User::model()->findAll($criteria);
        $this->render('/site/search', array('query' => $query, 'users' => $users));
    }

    public function actionLog()
    {
        $model=new Log('search');
        $model->unsetAttributes();
        if(isset($_GET['Log']))
            $model->attributes=$_GET['Log'];

        $this->render('log',array(
            'model'=>$model,
        ));
    }




}