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
        $model = CoursesMaterial::model()->findAll($criteria);
        $z = $model[0]->zindex;
        CoursesMaterial::model()->deleteAll($criteria);
        $criteria = new CDbCriteria();
        $criteria->compare("idCourse",$idCourse);
        $criteria->addCondition("zindex > ".$z);
        $materials = CoursesMaterial::model()->findAll($criteria);
        foreach ($materials as $item)
        {
            $item->zindex = $item->zindex-1;
            $item->save();
        }
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

    public function actionOrderMaterial()
    {
        $idMat = $_POST["idMat"];
        $idParentMat = $_POST["idParentMat"];
        $mat = CoursesMaterial::model()->findByPk($idMat);
        if ($idParentMat != 0)
        {
            $parentMat = CoursesMaterial::model()->findByPk($idParentMat);
            $needZIndex = $parentMat->zindex;
        }
        else
        {
            $parentMat = new CoursesMaterial();
            $needZIndex = 0;
        }
        if ($needZIndex<$mat->zindex)
        {
            $criteria = new CDbCriteria();
            $criteria->compare("idCourse",$mat->idCourse);
            $criteria->addCondition("zindex > ".$needZIndex);
            $criteria->addCondition("zindex < ".$mat->zindex);
            $materials = CoursesMaterial::model()->findAll($criteria);
            foreach ($materials as $item)
            {
                $item->zindex = $item->zindex+1;
                $item->save();
            }
            $mat->zindex = $needZIndex+1;
            $mat->save();
        } else
        {
            $criteria = new CDbCriteria();
            $criteria->compare("idCourse",$mat->idCourse);
            $criteria->addCondition("zindex > ".$mat->zindex);
            $criteria->addCondition("zindex <= ".$needZIndex);
            $materials = CoursesMaterial::model()->findAll($criteria);
            foreach ($materials as $item)
            {
                $item->zindex = $item->zindex-1;
                $item->save();
            }
            $mat->zindex = $needZIndex;
            $mat->save();
        }
    }

    public function actionGetMaterials()
    {
        $idCourse = $_POST["idCourse"];
        $this->renderPartial("/courses/materialTable", array("idCourse" => $idCourse));
    }


}
