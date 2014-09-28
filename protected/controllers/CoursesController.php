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
        if (Yii::app()->user->isStudent())
        {
            $resultCourses = array();
            foreach (Yii::app()->user->getModel()->groups as $group)
            {

                $courses = Course::getCoursesByGroup($group->id,$idTerm);
                $resultCourses = array_merge($resultCourses,$courses);
           }
            $this->renderPartial('coursesTable', array('courses' => $resultCourses));
           }
    }


}