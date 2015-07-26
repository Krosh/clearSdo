<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class CoursesController extends CController
{
    public $noNeedSidebar = false;
    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.TimezoneFilter'),
//            array('application.filters.AccessFilter'),
        );
    }

    public function actionGetCourses()
    {
        $idTerm = $_POST['idTerm'];
        Yii::app()->session['currentTerm'] = $idTerm;
        if (Yii::app()->user->isStudent())
        {
            $resultCourses = array();
            foreach (Yii::app()->user->getModel()->groups as $group)
            {
                $courses = Course::getCoursesByGroup($group->id,$idTerm);
                $resultCourses = array_merge($resultCourses,$courses);
            }
            $this->renderPartial('coursesTable', array('courses' => $resultCourses, 'isStudent' => true, 'idTerm' => $idTerm));
        }
        if (Yii::app()->user->isTeacher())
        {
            $courses = Course::getCoursesByAutor(Yii::app()->user->getId(),$idTerm);
            $this->renderPartial('coursesTable', array('courses' => $courses, 'isStudent' => false, 'idTerm' => $idTerm));
        }
    }

    public function actionAddTeacherToCourse()
    {
        $fio = $_POST["fio"];
        $idCourse = $_POST["idCourse"];
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition("fio",$fio);
        $user = User::model()->findAll($criteria);
        $model = new CoursesAutor();
        $model->idCourse = $idCourse;
        $model->idAutor = $user[0]->id;
        $model->save();
    }



    public function actionDeleteTeacher()
    {
        $idCourse = $_POST["idCourse"];
        $idTeacher = $_POST["idTeacher"];
        CoursesAutor::model()->deleteAll("idCourse = :idCourse and idAutor = :idTeacher",array(":idCourse" => $idCourse, ":idTeacher" => $idTeacher));
    }

    public function actionGetTeachers()
    {
        $this->renderPartial('teachers',array("idCourse" => $_POST["idCourse"]));
    }

    public function actionAddGroupToCourse()
    {
        if (is_array($_POST["idGroup"]))
            $idGroup = $_POST["idGroup"][0];
        else
            $idGroup = $_POST["idGroup"];
        $idTerm = $_POST["idTerm"];
        $idCourse = $_POST["idCourse"];
        $model = new CoursesGroup();
        $model->idTerm = $idTerm;
        $model->idCourse = $idCourse;
        $model->idGroup = $idGroup;
        $model->save();
    }

    public function actionDeleteGroup()
    {
        $idCourse = $_POST["idCourse"];
        if (is_array($_POST["idGroup"]))
            $idGroup = $_POST["idGroup"][0];
        else
            $idGroup = $_POST["idGroup"];
        $idTerm = $_POST["idTerm"];
        CoursesGroup::model()->deleteAll("idTerm = :idTerm AND idCourse = :idCourse and idGroup = :idGroup",array(":idCourse" => $idCourse, ":idGroup" => $idGroup, ':idTerm' => $idTerm));
    }

    public function actionGetGroups()
    {
        $this->renderPartial('groups',array("idCourse" => $_POST["idCourse"], "idTerm" => $_POST["idTerm"]));
    }

    public function actionCreate()
    {
        $model = new Course();
        $model->save();
        $this->redirect($this->createUrl("/editCourse?idCourse=".$model->id));
    }

    public function actionFullDeleteCourse($id)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idCourse',$id);
        $criteria->compare('idAutor', Yii::app()->user->getId());
        if (CoursesAutor::model()->count($criteria) == 0)
            return;
        Course::model()->deleteByPk($id);
    }

    public function actionCalendar($id)
    {
        $this->noNeedSidebar = true;
        $this->layout = "//layouts/full";
        $model = Course::model()->findByPk($id);
        $alreadyHasDateMaterials = array();
        $withoutDateMaterials = array();
        foreach ($model->coursesControlMaterial as $item)
        {
            if ($item->dateAction != "0000-00-00" && $item->dateAction != "")
                $alreadyHasDateMaterials[] = $item;
            else
                $withoutDateMaterials[] = $item;
        }
        $this->render("calendar", array("model" => $model, "alreadyHasDateMaterials" => $alreadyHasDateMaterials, "withoutDateMaterials" => $withoutDateMaterials));
    }

    public function actionAjaxGetCalendarEvents($start, $end, $id, $_)
    {
        $model = Course::model()->findByPk($id);
        $alreadyHasDateMaterials = array();
        foreach ($model->coursesControlMaterial as $item)
        {
            if (strtotime($item->dateAction) >= strtotime($start) && strtotime($item->dateAction) <= strtotime($end))
            {
                $mas = array();
                $mas['title'] = $item->controlMaterial->title;
                $mas['start'] = $item->dateAction;
                $mas['idCourseControlMaterial'] = $item->id;
                $alreadyHasDateMaterials[] = $mas;
            }
        }
        echo json_encode($alreadyHasDateMaterials);
    }

    public function actionAjaxSetEventTime()
    {
        $idCourseControlMaterial = $_POST['idCourseControlMaterial'];
        $date = $_POST['date'];
        $model = CoursesControlMaterial::model()->findByPk($idCourseControlMaterial);
        $model->dateAction = $date;
        $model->save();
    }



}