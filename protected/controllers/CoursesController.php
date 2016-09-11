<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class CoursesController extends CController
{
    public $layout = "/layouts/full";
    public $breadcrumbs;
    public $noNeedSidebar = false;
    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.TimezoneFilter'),
            array('application.filters.AccessFilter'),
        );
    }

    public function actionEdit($id)
    {
        $course = Course::model()->findByPk($id);
        if ($course == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }
        Yii::app()->session['currentCourse'] = $id;
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/courses/edit",array("id" => $id)))
        );
        if(isset($_POST['Course']))
        {
            $course->attributes=$_POST['Course'];
            if($course->save())
                $this->refresh();
        }

        $this->render('edit', array('model' => $course));
    }

    public function actionView($id)
    {
        $course = Course::model()->findByPk($id);
        if ($course == null)
        {
            // Бросить ошибку
        }
        Yii::app()->session['currentCourse'] = $id;
        $this->render('view', array('model' => $course));
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
        $id = $_POST["id"];
        $idCourse = $_POST["idCourse"];
        CoursesAutor::model()->deleteAll("idCourse = :idCourse AND idAutor = :idAutor", array(":idCourse" => $idCourse, ":idAutor" => $id));
        $model = new CoursesAutor();
        $model->idCourse = $idCourse;
        $model->idAutor = $id;
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
        $this->redirect($this->createUrl("/courses/edit?id=".$model->id));
    }

    public function actionFullDeleteCourse($id)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idCourse',$id);
        $criteria->compare('idAutor', Yii::app()->user->getId());
        if (CoursesAutor::model()->count($criteria) == 0)
            return;
        $course = Course::model()->findByPk($id);
        $course->delete();
    }

    public function actionCalendar($id)
    {
        $model = Course::model()->findByPk($id);
        $this->breadcrumbs=array(
            $model->title => array($this->createUrl("/courses/edit",array("id" => $id))),
            "Календарь" => "",
        );
        $this->noNeedSidebar = true;
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
        $alreadyHasDateMaterials = array();
        if ($id > 0)
        {
            $model = Course::model()->findByPk($id);
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
        } else
        {
            $events = array();
            $idTerm = Yii::app()->session['currentTerm'];
            foreach (Yii::app()->user->getModel()->groups as $group)
            {
                $courses = Course::getCoursesByGroup($group->id,$idTerm);
                foreach ($courses as $course)
                {
                    foreach ($course->coursesControlMaterial as $item)
                    {
                        if (strtotime($item->dateAction) >= strtotime($start) && strtotime($item->dateAction) <= strtotime($end))
                        {
                            if (!isset($events[$item->dateAction]))
                            {
                                $events[$item->dateAction] = array();
                            }
                            $mas = array();
                            $mas['title'] = $item->controlMaterial->title." | ".$course->title;
                            $mas['start'] = $item->dateAction;
                            $mas['idCourseControlMaterial'] = $item->id;
                            $events[$item->dateAction][] = $mas;
                        }
                    }
                }
                foreach (array_keys($events) as $date)
                {
                    $mas = array();
                    $mas['title'] = count($events[$date]);
                    $mas['start'] = $date;
                    $s = "";
                    foreach ($events[$date] as $item)
                    {
                        $s.= $item['title']."<br>";
                    }
                    $mas['description'] = $s;
                    $alreadyHasDateMaterials[] = $mas;
                }
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

    public function actionGetCoursesFiles($idCourse)
    {
        if(extension_loaded('zip'))
        {
            $course = Course::model()->findByPk($idCourse);
            $learnMaterials = CoursesMaterial::getAccessedLearnMaterials($course->id);
            $zip = new ZipArchive();
            $zip_name = $course->title.".zip";
            if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
            {
                return;
            }
            $currentDirectory = "";
            $visibleFolderStatus = true;
            foreach ($learnMaterials as $item)
            {
                $visibleStatus = ($item->accessInfo != null && $item->accessInfo->hasAccess);
                if ($item->category == MATERIAL_TITLE)
                    $visibleFolderStatus = $visibleStatus;
                if (!$visibleFolderStatus || !$visibleStatus)
                    continue;
                if ($item->category == MATERIAL_TITLE)
                {
                    // Создаем новую директорию в архиве
                    $currentDirectory = StringHelper::translitText($item->title)."/";
                    $zip->addEmptyDir($currentDirectory);
                }
                else
                {
                    // добавляем файлы в zip архив
                    $path = $item->getAsFile();
                    $zip->addFile($path,$currentDirectory.StringHelper::translitText($item->getDownloadableName()));
                }
            }
            $zip->close();
            if(file_exists($zip_name))
            {
                header('Content-type: application/zip');
                header('Content-Disposition: attachment; filename="'.$zip_name.'"');
                readfile($zip_name);
                unlink($zip_name);
            }
        }
    }



}