<?php

class UserController extends CController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/full';

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

    public function actionAjaxGetStudentsToSlick()
    {
        $idGroup = Yii::app()->request->getParam("idGroup");
        $criteria = new CDbCriteria();
//        $criteria->addCondition("id <> ".Yii::app()->user->id);
        $criteria->compare("role",ROLE_STUDENT);
        $users = User::model()->findAll($criteria);
        $arr = array();
        foreach ($users as $user)
        {
            $role = "Студент ";
            $groups = StudentGroup::model()->findAll("idStudent = :id", array(':id' => $user->id));
            $groupNames = array();
            $flag = true;
            foreach ($groups as $item)
            {
                if ($item->idGroup == $idGroup)
                {
                    $flag = false;
                    break;
                }
                $group = Group::model()->findByPk($item->idGroup);
                $groupNames[] = $group->Title;
            }
            if (!$flag)
                continue;
            $item = array();
            $item['value'] = $user->id;
            $item['text'] = $user->fio;
            $item['selected'] = false;
            $item['description'] = implode(",",$groupNames);
            $item['imageSrc'] = $user->getAvatarPath(AVATAR_SIZE_MINI);
            $arr[] = $item;
        }
        $item = array();
        $item['value'] = -1;
        $item['text'] = "Никого не нашли :(";
        $item['selected'] = false;
        $item['description'] = "Попробуйте уточнить данные поиска";
        $item['imageSrc'] = "";
        $arr[] = $item;
        echo json_encode($arr);

    }

    public function actionGetStudents()
    {
        $name = $_POST["searchText"];
        header('Content-type: text/html; charset=UTF-8');
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition("fio", $name);
        $criteria->compare("role",0);
        $model = User::model()->findAll($criteria);
        $answer = array();
        foreach ($model as $item)
        {
            $res = array('id' => $item->id, 'fio' => $item->fio);
            $answer[] = $res;
        }
        echo json_encode($answer);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if ($_POST['haveNewPassword'])
            {
                $model->password = md5($_POST["newPassword"]);
                $model->dateChangePassword = date("Y-m-d H:i:s");
                Yii::app()->user->setFlash("codeMessage","success");
                Yii::app()->user->setFlash("message","Пароль изменен");
                $flag = false;
            }
            $model->newAvatar = $_POST['User']['newAvatar'];
            if($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id,$goToGroup = -1)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['User']))
        {
            $flag = true;
            $model->attributes=$_POST['User'];
            if ($_POST['haveNewPassword'])
            {
                $model->password = md5($_POST["newPassword"]);
                $model->dateChangePassword = date("Y-m-d H:i:s");
                Yii::app()->user->setFlash("codeMessage","success");
                Yii::app()->user->setFlash("message","Пароль изменен");
                $flag = false;
            }
            $model->newAvatar = $_POST['User']['newAvatar'];
            if($model->save() && $flag)
            {
                if ($goToGroup>-1)
                    $this->redirect(array($this->createUrl('/group/update',array('id' => $goToGroup))));
                else
                    $this->redirect(array('admin'));
            } else
            {
                if ($flag)
                {
                    Yii::app()->user->setFlash("codeMessage","error");
                    Yii::app()->user->setFlash("message","Ошибка при загрузке аватара, выберите другой");
                }
            }
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new User('search');
        $model->unsetAttributes();  // clear any default values
        if (Yii::app()->user->getState('userSearchParams') == null)
        {
            Yii::app()->user->setState('userSearchParams', array());
        }

        if(isset($_GET['User']))
        {
            Yii::app()->user->setState('userSearchParams', array_merge(Yii::app()->user->getState('userSearchParams'),$_GET['User']));
            $model->attributes=Yii::app()->user->getState('userSearchParams');
        }
        else
        {
            $searchParams = Yii::app()->user->getState('userSearchParams');
            if (isset($searchParams))
            {
                $model->attributes = $searchParams;
            }
        }

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=User::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCheckOnAuthenticate()
    {
        $model = new User();
        $model->attributes = $_POST['User'];
        $identity=new UserIdentity($model->login,$model->password);
        if ($identity->authenticate())
        {
            echo "1";
        }
        else
            echo "0";
    }

    // Отправка мыла с восстановлением пароля
    public function actionSendForgotMessage()
    {
        $user = User::model()->find("email = '".$_POST['User']["email"]."'");

        if($user->activateEmail()) {
            echo "1";
        }
        else
            echo "0";
    }

    private function randomPassword() {
        $alphabet = "0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function actionActivation($idUser, $cache)
    {
        $this->layout = '//layouts/main';
        $user = $this->loadModel($idUser);
        $newpass = $this->randomPassword();
        if ($user->activationCache != $cache)
            throw new CHttpException(404,'The requested page does not exist.');
        $user->password = md5($newpass);
        $user->save();

        // echo "Новые параметы для входа:<br>";
        // echo "Ваш логин:[".$user->login."], ваш пароль:[1234]";

        $this->render("activation", array(
            "login" => $user->login,
            "newpassword" => $newpass
        ));
    }

    public function actionSetNewEmail($idUser, $cache)
    {
        $this->layout = '//layouts/main';
        $user = $this->loadModel($idUser);
        if ($user->activationCache != $cache)
            throw new CHttpException(404,'The requested page does not exist.');
        $user->email = $user->new_email;
        $user->save();

        // echo "Новые параметы для входа:<br>";
        // echo "Ваш логин:[".$user->login."], ваш пароль:[1234]";

        $this->render("setNewEmail", array(
            "email" => $user->email,
        ));
    }

}
