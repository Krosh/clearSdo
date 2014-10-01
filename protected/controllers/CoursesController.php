<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class CoursesController extends CController
{
    public function filters()
    {
        return array(
            array('application.filters.TimezoneFilter'),
            array('application.filters.AccessFilter'),
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
        $groupTitle = $_POST["groupTitle"];
        $termTitle = $_POST["termTitle"];
        $idCourse = $_POST["idCourse"];
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition("Title",$groupTitle);
        $group = Group::model()->findAll($criteria);
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition("title",$termTitle);
        $term = Term::model()->findAll($criteria);
        $model = new CoursesGroup();
        $model->idTerm = $term[0]->id;
        $model->idCourse = $idCourse;
        $model->idGroup = $group[0]->id;
        $model->save();
    }

    public function actionDeleteGroup()
    {
        $idCourse = $_POST["idCourse"];
        $idGroup = $_POST["idGroup"];
        CoursesGroup::model()->deleteAll("idCourse = :idCourse and idGroup = :idGroup",array(":idCourse" => $idCourse, ":idGroup" => $idGroup));
    }

    public function actionGetGroups()
    {
        $this->renderPartial('groups',array("idCourse" => $_POST["idCourse"]));
    }

    public function actionCreate()
    {
        $model = new Course();
        $model->title = "Новый курс";
        $model->save();
        $ca = new CoursesAutor();
        $ca->idAutor = Yii::app()->user->getId();
        $ca->idCourse = $model->id;
        $ca->save();
        $cg = new CoursesGroup();
        $cg->idCourse = $model->id;
        $cg->idGroup = -1;
        $cg->idTerm = Yii::app()->session['currentTerm'];
        $cg->save();
        $this->redirect($this->createUrl("/editCourse?idCourse=".$model->id));
    }

}