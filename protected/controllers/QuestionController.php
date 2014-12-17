<?php

class QuestionController extends CController
{
    public $noNeedJquery = false;
    public $layout = "/layouts/main";
    public $breadcrumbs;

    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionDeleteQuestion()
    {
        $idQuestion = $_POST["idQuestion"];
        $idControlMaterial = $_POST["idControlMaterial"];
        $criteria = new CDbCriteria();
        $criteria->compare("idQuestion",$idQuestion);
        $criteria->compare("idControlMaterial",$idControlMaterial);
        $model = QuestionsControlMaterial::model()->findAll($criteria);
        $z = $model[0]->zindex;
        QuestionsControlMaterial::model()->deleteAll($criteria);
        $criteria = new CDbCriteria();
        $criteria->compare("idControlMaterial",$idControlMaterial);
        $criteria->addCondition("zindex > ".$z);
        $materials = QuestionsControlMaterial::model()->findAll($criteria);
        foreach ($materials as $item)
        {
            $item->zindex = $item->zindex-1;
            $item->save();
        }
    }

    public function actionAddExistQuestion()
    {
        $courseMat = new QuestionsControlMaterial();
        $courseMat->idControlMaterial = $_POST["idControlMaterial"];
        $courseMat->idQuestion = $_POST["idQuestion"];
        $courseMat->zindex = QuestionsControlMaterial::model()->count("idControlMaterial = ".$_POST["idControlMaterial"])+1;
        $courseMat->save();
    }

    public function actionOrderQuestions()
    {

        $idMat = $_POST["idMat"];
        $idParentMat = $_POST["idParentMat"];
        $mat = QuestionsControlMaterial::model()->findByPk($idMat);
        if ($idParentMat != 0)
        {
            $parentMat = QuestionsControlMaterial::model()->findByPk($idParentMat);
            $needZIndex = $parentMat->zindex;
        }
        else
        {
            $parentMat = new QuestionsControlMaterial();
            $needZIndex = 0;
        }
        if ($needZIndex<$mat->zindex)
        {
            $criteria = new CDbCriteria();
            $criteria->compare("idControlMaterial",$mat->idControlMaterial);
            $criteria->addCondition("zindex > ".$needZIndex);
            $criteria->addCondition("zindex < ".$mat->zindex);
            $materials = QuestionsControlMaterial::model()->findAll($criteria);
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
            $criteria->compare("idControlMaterial",$mat->idControlMaterial);
            $criteria->addCondition("zindex > ".$mat->zindex);
            $criteria->addCondition("zindex <= ".$needZIndex);
            $materials = QuestionsControlMaterial::model()->findAll($criteria);
            foreach ($materials as $item)
            {
                $item->zindex = $item->zindex-1;
                $item->save();
            }
            $mat->zindex = $needZIndex;
            $mat->save();
        }
    }

    public function actionGetQuestions()
    {
        $idControlMaterial = $_POST["idControlMaterial"];
        $this->renderPartial("/controlMaterial/questionTable", array("idControlMaterial" => $idControlMaterial));
    }


    public function actionCreate($idMaterial)
    {
        $model = new Question();
        $model->type = 1;
        $model->weight = 1;
        $model->content = "Новый вопрос";
        $model->save();
        $ca = new QuestionsControlMaterial();
        $ca->idControlMaterial = $idMaterial;
        $ca->idQuestion = $model->id;
        $ca->zindex = QuestionsControlMaterial::model()->count("idControlMaterial = ".$idMaterial)+1;
        $ca->save();
        $this->redirect($this->createUrl("/question/edit?id=".$model->id."&idMaterial=".$idMaterial));
    }

    public function actionEdit($id,$idMaterial)
    {
        $this->noNeedJquery = true;
        $model = Question::model()->findByPk($id);
        $course = Course::model()->findByPk(Yii::app()->session['currentCourse']);
        $test = ControlMaterial::model()->findByPk($idMaterial);
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => Yii::app()->session['currentCourse']))),
            $test->title => array($this->createUrl("/controlMaterial/edit",array("idMaterial" => $idMaterial))),
            'Вопрос'=> array($this->createUrl("/question/edit",array("idMaterial" => $idMaterial,"id" => $id))),
        );
        if (isset($_POST['Question']))
        {
            $attrs = $_POST['Question'];
            $model->content = $attrs['content'];
            $model->fee = $attrs['fee'];
            $model->type = $attrs['type'];
            $model->random_answer = $attrs['random_answer'];
            $model->weight = $attrs['weight'];
//          //            $model->attributes=$_POST['Question'];
            if ($model->save())
            {
                $this->redirect($this->createUrl("/controlMaterial/edit",array("idMaterial" => $idMaterial)));
            }
        }
        $this->render("/question/create", array("model" => $model));
    }


}
