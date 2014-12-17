<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class ActiveTestFilter extends CFilter {
    public function preFilter($filterChain) {
        if (Yii::app()->user->isGuest)
            return true;
        if (isset(Yii::app()->session['currentTestGo']))
        {
            $idGo = Yii::app()->session['currentTestGo'];
            if ($idGo == -1)
                return true;
            Yii::app()->controller->redirect(Yii::app()->controller->createUrl("/controlMaterial/question"));
        }
        $criteria = new CDbCriteria();
        $criteria->compare("idUser",Yii::app()->user->getId());
        $criteria->addCondition("dateStart < NOW()");
        $criteria->addCondition("dateEnd IS NULL");
        $activeTest = UserControlMaterial::model()->find($criteria);
        if ($activeTest != null)
        {
            // Заполняем сессию нужными данными
            $testModel = ControlMaterial::model()->findByPk($activeTest->idControlMaterial);
            $questionsFromDb = Question::getQuestionsByControlMaterial($activeTest->idControlMaterial);
            // В сессию записываем два массива
            // $questionsId - массив с номерами вопросов
            // $isAnswered - массив с флагами, отвечен ли этот вопрос(на случай пропусков вопросов)
            if ($testModel->question_show_count == -1)
                $count = count($questionsFromDb);
            else
                $count = $testModel->question_show_count;
            $flag = array();
            $ids = array();
            for ($i = 0; $i<count($questionsFromDb); $i++)
            {
                $flag[$i] = true;
            }
            for ($i = 0; $i<$count; $i++)
            {
                do
                {
                    $j = rand(0, count($questionsFromDb)-1);
                }
                while (!$flag[$j]);
                $ids[$i] = $j;
                $flag[$j] = false;
            }
            sort($ids);
            if ($testModel->question_random) shuffle($ids);
            $questions = array();
            $flags = array();
            foreach($ids as $item)
            {
                array_push($questions, $questionsFromDb[$item]->id);
                array_push($flags, true);
            }
            $criteria = new CDbCriteria();
            $criteria->compare("idUserControlMaterial",$activeTest->id);
            $alreadyAnsweredQuestions = UserAnswer::model()->findAll($criteria);
            foreach ($alreadyAnsweredQuestions as $item)
            {
                if (array_search($item->idQuestion, $questions) !== 0)
                {
                    $flags[array_search($item->idQuestion, $questions)] = false;
                    continue;
                }
                // Ищем вопрос среди запомненных, которого нет в уже отвеченных
                for ($i = 0; $i < count($questions); $i++)
                {
                    $curQuestionId = $questions[$i];
                    $flag = true;
                    $idQuestion = 0;
                    foreach ($alreadyAnsweredQuestions as $alAnswQuestion)
                    {
                        if ($alAnswQuestion->idQuestion == $curQuestionId)
                        {
                            $flag = false;
                            $idQuestion = $alAnswQuestion->idQuestion;
                            break;
                        }
                    }
                    if (!$flag) break;
                    $questions[$i] = $idQuestion;
                    $flags[$i] = false;
                }
            }

            Yii::app()->session['totalQuestions'] = $count;
            Yii::app()->session['questions'] = $questions;
            Yii::app()->session['flags'] = $flags;
            Yii::app()->session['currentTest'] = $activeTest->idControlMaterial;
            Yii::app()->session['currentTestGo'] = $activeTest->id;
            Yii::app()->session['currentQuestion'] = 0;

            Yii::app()->controller->redirect(Yii::app()->controller->createUrl("/controlMaterial/question"));
        }
        return true;
    }
    public function postFilter($filterChain) {}
}