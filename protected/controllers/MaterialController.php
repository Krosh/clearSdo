<?php

class MaterialController extends CController
{
    public $layout='//layouts/full';

    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionChangeAccess()
    {
        $newAccess = $_POST["access"];
        $idControlMaterial = $_POST["idMaterial"];
        $material = ControlMaterial::model()->findByPk($idControlMaterial);
        $material->access = $newAccess;
        $material->save();
        echo $material->id." ".$material->access;
    }


    public function actionDeleteMaterial()
    {
        $idControlMaterial = $_POST["idMaterial"];
        $idCourse = $_POST["idCourse"];

        $criteria = new CDbCriteria();
        $criteria->compare("idControlMaterial",$idControlMaterial);
        $criteria->compare("idCourse",$idCourse);
        $model = CoursesControlMaterial::model()->find($criteria);
        $z = $model->zindex;
        CoursesControlMaterial::model()->deleteAll($criteria);
        AccessControlMaterial::model()->deleteAll($criteria);
        $criteria = new CDbCriteria();
        $criteria->compare("idCourse",$idCourse);
        $criteria->addCondition("zindex > ".$z);
        $materials = CoursesControlMaterial::model()->findAll($criteria);
        foreach ($materials as $item)
        {
            $item->zindex = $item->zindex-1;
            $item->save();
        }
    }

    public function actionAddExistMaterial()
    {
        $material = ControlMaterial::model()->findByPk($_POST["idMaterial"]);
        $material->addToCourse($_POST["idCourse"]);
        $material->addCommonAccess($_POST["idCourse"]);
    }

    public function actionOrderMaterial()
    {
        $idMat = $_POST["idMat"];
        $idParentMat = $_POST["idParentMat"];
        $mat = CoursesControlMaterial::model()->findByPk($idMat);
        if ($idParentMat != 0)
        {
            $parentMat = CoursesControlMaterial::model()->findByPk($idParentMat);
            $needZIndex = $parentMat->zindex;
        }
        else
        {
            $parentMat = new CoursesControlMaterial();
            $needZIndex = 0;
        }
        if ($needZIndex<$mat->zindex)
        {
            $criteria = new CDbCriteria();
            $criteria->compare("idCourse",$mat->idCourse);
            $criteria->addCondition("zindex > ".$needZIndex);
            $criteria->addCondition("zindex < ".$mat->zindex);
            $materials = CoursesControlMaterial::model()->findAll($criteria);
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
            $materials = CoursesControlMaterial::model()->findAll($criteria);
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
        $this->renderPartial("/controlMaterial/table", array("idCourse" => $idCourse));
    }




}
