<?php

class AnswerController extends CController
{
    public $layout = "/layouts/main";

    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionDeleteMaterial()
    {
        $model = Answer::model()->findByPk($_POST["idAnswer"]);
        $z = $model->zindex;
        $q = $model->question;
        Answer::model()->deleteByPk($_POST["idAnswer"]);
        $criteria = new CDbCriteria();
        $criteria->compare("question",$q);
        $criteria->addCondition("zindex > ".$z);
        $materials = Answer::model()->findAll($criteria);
        foreach ($materials as $item)
        {
            $item->zindex = $item->zindex-1;
            $item->save();
        }
    }

    public function actionOrderMaterial()
    {
        $idMat = $_POST["idMat"];
        $idParentMat = $_POST["idParentMat"];
        $mat = Answer::model()->findByPk($idMat);
        if ($idParentMat != 0)
        {
            $parentMat = Answer::model()->findByPk($idParentMat);
            $needZIndex = $parentMat->zindex;
        }
        else
        {
            $parentMat = new Answer();
            $needZIndex = 0;
        }
        if ($needZIndex<$mat->zindex)
        {
            $criteria = new CDbCriteria();
            $criteria->compare("question",$mat->question);
            $criteria->addCondition("zindex > ".$needZIndex);
            $criteria->addCondition("zindex < ".$mat->zindex);
            $materials = Answer::model()->findAll($criteria);
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
            $criteria->compare("question",$mat->question);
            $criteria->addCondition("zindex > ".$mat->zindex);
            $criteria->addCondition("zindex <= ".$needZIndex);
            $materials = Answer::model()->findAll($criteria);
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
        $idQuestion = $_POST["idQuestion"];
        $this->renderPartial("/answer/answerTable", array("idQuestion" => $idQuestion));
    }

    public function actionChangeAnswer()
    {
        $idAnswer = $_POST["idAnswer"];
        $content = $_POST["content"];
        $right = $_POST["right"];
        $model = Answer::model()->findByPk($idAnswer);
        $model->content = $content;
        $model->right = $right;
        $model->save();
    }


    public function actionCreate()
    {
        $idQuestion = $_POST['idQuestion'];
        $model = new Answer();
        $model->content = "Новый ответ~Соответствующий вариант";
        $model->question = $idQuestion;
        $model->zindex = Answer::model()->count("question = ".$idQuestion)+1;
        $model->save();
    }



}
