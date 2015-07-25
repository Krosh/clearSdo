<?php

class GroupController extends CController
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

    function getContent($url, array $post){
        $ecx = count($post);
        while($ecx--){
            @$post['fields'].=key($post).'='.array_shift($post).'&';
        }
        $post = rtrim($post['fields'], '&');
        try {
            $crl = curl_init($url);
            curl_setopt_array($crl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
                    CURLOPT_FOLLOWLOCATION => 1
                )
            );
            if(!($html = curl_exec($crl))) throw new Exception();
            curl_close($crl);
            return $html;

        } catch(Exception $e){
            return FALSE;
        }
    }

    public function actionGetTimetable()
    {
        $this->layout = null;
         $idGroup = $_POST['idGroup'];
        $url = 'http://www.altstu.ru/main/schedule/';
        $group = Group::model()->findByPk($idGroup);
        $text = $this->getContent($url,array("group" => $group->id_altstu));
        preg_match_all("-<div class=\"schedule\">(.*)<div id=\"aside\">-s",$text,$matches);
        $text = $matches[1][0];

        $DAYS_NAMES = array("Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
        $WEEK_NAMES = array("1 неделя","2 неделя");
        $weekPattern = '~(<h3[^>]*>([0-9А-Яа-я ]*)</h3>|Экзамены)~u';
        $dayPattern = '#<th colspan="4" [^>]*>([А-Яа-я ]*)</th>#u';
        $lessonPattern = '#<tr>[.|\s]*<td>.*</td>\s*<td>.*</td>\s*<td.*>.*</td>\s*<td>.*</td>\s*</tr>#u';
        $lessonInfoPattern = '#<td[^>]*>(.*)</td>#u';
        $weeks = preg_split($weekPattern,$text,null,PREG_SPLIT_DELIM_CAPTURE);
        Timetable::model()->deleteAll("idGroup = ".$group->id);
        for ($numWeek = 2; $numWeek<count($weeks)-1; $numWeek+=3)
        {
            $nameWeek = $weeks[$numWeek];
            $textWeek = $weeks[$numWeek+1];
            $days = preg_split($dayPattern,$textWeek,null,PREG_SPLIT_DELIM_CAPTURE);
            for ($numDays = 1; $numDays<count($days); $numDays+=2)
            {
                $nameDay = $days[$numDays];
                $textDay = $days[$numDays+1];
                $lessonInfos = preg_split($lessonInfoPattern,$textDay,null,PREG_SPLIT_DELIM_CAPTURE);
                for ($numLessonInfo = 0; $numLessonInfo<count($lessonInfos)-1; $numLessonInfo+=8)
                {
                    $time = $lessonInfos[$numLessonInfo+1];
                    $name = strip_tags($lessonInfos[$numLessonInfo+3]);
                    $room = strip_tags($lessonInfos[$numLessonInfo+5]);
                    $teacher = strip_tags($lessonInfos[$numLessonInfo+7]);
                    $timetable = new Timetable();
                    $timetable->idGroup = $group->id;
                    $timetable->day = array_search($nameDay,$DAYS_NAMES);
                    $timetable->numWeek = array_search($nameWeek,$WEEK_NAMES);
                    $timetable->time = $time;
                    $timetable->name = $name;
                    $timetable->room = $room;
                    $timetable->teacher = $teacher;
                    $timetable->save();
                }

            }
        }
        return;
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
