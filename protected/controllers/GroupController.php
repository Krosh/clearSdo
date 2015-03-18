<?php

class GroupController extends CController
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Group;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Group']))
        {
            $model->attributes=$_POST['Group'];
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
    public function actionUpdate($id)
    {
        $this->noNeedJquery = true;
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Group']))
        {
            $model->attributes=$_POST['Group'];
            if($model->save())
                $this->redirect(array('admin'));
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
        $this->noNeedJquery = true;
        $model=new Group('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Group']))
            $model->attributes=$_GET['Group'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }


    public function actionDeleteFromGroup($idStudent,$idGroup)
    {
        $criteria = new CDbCriteria();
        $criteria->compare("idGroup",$idGroup);
        $criteria->compare("idStudent",$idStudent);
        StudentGroup::model()->deleteAll($criteria);
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/group/update?id='.$idGroup));
    }


    public function actionAddToGroup()
    {
        $fio = $_POST["fio"];
        $user = User::model()->find("fio = :fio",array(":fio" => $fio));
        if ($user != null)
        {
            $group = $_POST["group"];
            $model = new StudentGroup();
            $model->idGroup = $group;
            $model->idStudent = $user->id;
            $model->save();
        }
    }

    public function actionLoadStudentsFromExcel()
    {
        srand();
        include(Yii::getPathOfAlias("webroot.protected.components.excel_reader2").".php");
        $idGroup = $_POST["idGroup"];
        $group = Group::model()->findByPk($idGroup);
        if (true || isset($_FILES['filename']))
        {
              if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
              {
                  move_uploaded_file($_FILES["filename"]["tmp_name"], $_FILES["filename"]["name"]);
                  $path = $_FILES["filename"]["name"];
              } else {
                  echo("error");
                  return;
              }
            $Excel = new Spreadsheet_Excel_Reader();
            $Excel->setOutputEncoding('utf-8');
            $Excel->read($path);
            $count = $Excel->sheets[0]['numRows'];
            $n = 0;
            for ($num=1; $num<=$count; $num++)
            {
                if ($Excel->sheets[0]['cells'][$num][1] == "")
                    break;
                $student = new User();
                $student->fio = $Excel->sheets[0]['cells'][$num][1]." ".$Excel->sheets[0]['cells'][$num][2]." ".$Excel->sheets[0]['cells'][$num][3];
                $student->role = "student";
                $student->login = StringHelper::translitText(str_replace("-","",$group->Title)).StringHelper::translitText(substr($Excel->sheets[0]['cells'][$num][1],0,2)).StringHelper::translitText(substr($Excel->sheets[0]['cells'][$num][2],0,2)).StringHelper::translitText(substr($Excel->sheets[0]['cells'][$num][3],0,2));
                $student->password = rand(111111,999999);
                if ($student->save())
                {
                    $sg = new StudentGroup();
                    $sg->idStudent = $student->id;
                    $sg->idGroup = $idGroup;
                    $sg->save();
                };
            }
            unlink($path);
        }
    }



    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Group the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Group::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Group $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='group-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
