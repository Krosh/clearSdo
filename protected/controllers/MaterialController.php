<?php

class MaterialController extends CController
{
    public $layout = "/layouts/main";

    public function filters()
    {
        return array(
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionDeleteLearnMaterial()
    {
        $idMaterial = $_POST["idMaterial"];
        $idCourse = $_POST["idCourse"];
        $criteria = new CDbCriteria();
        $criteria->compare("idMaterial",$idMaterial);
        $criteria->compare("idCourse",$idCourse);
        CoursesMaterial::model()->deleteAll($criteria);
    }

    public function actionAddLearnMaterial()
    {
        $mat = new LearnMaterial();
        $mat->idAutor = Yii::app()->user->getId();
        $mat->title = $_POST["LearnMaterial"]["title"];
        $mat->category = $_POST["LearnMaterial"]["category"];
        $mat->category == MATERIAL_LINK ? $mat->path = $_POST["LinkPath"]:$mat->path = $_FILES['filePath']['name'];
        $mat->save();
        $courseMat = new CoursesMaterial();
        $courseMat->idCourse = $_POST["idCourse"];
        $courseMat->idMaterial = $mat->id;
        $courseMat->zindex = CoursesMaterial::model()->count("idCourse = ".$_POST["idCourse"])+1;
        $courseMat->save();
    }

    public function actionAddExistLearnMaterial()
    {
        $courseMat = new CoursesMaterial();
        $courseMat->idCourse = $_POST["idCourse"];
        $courseMat->idMaterial = $_POST["idMaterial"];
        $courseMat->zindex = CoursesMaterial::model()->count("idCourse = ".$_POST["idCourse"])+1;
        $courseMat->save();
    }

    public function actionGetMaterials()
    {
        $idCourse = $_POST["idCourse"];
        $this->renderPartial("/courses/materialTable", array("idCourse" => $idCourse));
    }


}
