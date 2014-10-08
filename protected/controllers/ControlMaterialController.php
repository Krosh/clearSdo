<?php

class ControlMaterialController extends CController
{
    public $layout = "/layouts/main";
    public $noNeedJquery = false;
    public $breadcrumbs;

    public function filters()
    {
        return array(
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }


    public function actionStartTest($idTest)
    {
        // TODO:: Генерация последовательности вопросов в зависимости от настроек теста
        $testModel = $this->loadModel($idTest);
        $questionsFromDb = Question::getQuestionsByControlMaterial($idTest);
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
        Yii::app()->session['totalQuestions'] = $count;
        Yii::app()->session['questions'] = $questions;
        Yii::app()->session['flags'] = $flags;
        Yii::app()->session['currentTest'] = $idTest;
        $model = new UserControlMaterial();
        $model->idControlMaterial = $idTest;
        $model->idUser = Yii::app()->user->getId();
        $model->dateStart = date("Y-m-d H:i:s");
        $model->save();
        Yii::app()->session['currentTestGo'] = $model->id;
        Yii::app()->session['currentQuestion'] = 0;
        $this->redirect('/controlMaterial/question');
    }

    // Рендер вопроса
    public function actionQuestion()
    {
        $this->layout = "//layouts/main";
        $i = Yii::app()->session['currentQuestion'];
        // Получениие информации о вопросе и вариантах ответа
        $idQuestion = Yii::app()->session['questions'][$i];
        $question = Question::model()->findByPk($idQuestion);
        $criteria = new CDbCriteria();
        $criteria->compare('question', $idQuestion);
        $criteria->order = 'zindex';
        $answers = Answer::model()->findAll($criteria);

        // Считаем время
        $currentTestGo = UserControlMaterial::model()->findByPk(Yii::app()->session['currentTestGo']);
        $dateTime = new DateTime($currentTestGo->dateStart);
        $test = ControlMaterial::model()->findByPk($currentTestGo->idControlMaterial);
        $addTimeValue = $test->dotime;
        $dateTime->modify("+$addTimeValue minute");

        // Так как у нас php<5.3 ТО передаем такой костыль
        $this->render('/question/viewQuestion', array('question' => $question, 'answers' => $answers, 'endTime' => $dateTime->format("U")));
    }

    public function actionNextQuestion()
    {
      /*  $flag = false;
        $flag = $flag || isset($_POST['answer']);
        for ($i = 0; $i<10; $i++)
        {
            $flag = $flag || isset($_POST['answer'.$i]);
        }
        $flag = true;
       */ if (true)
        {
            $questionType = Question::model()->findByPk(Yii::app()->session['questions'][Yii::app()->session['currentQuestion']])->type;
            if ($questionType == QUESTION_RADIO || $questionType == QUESTION_NUMERIC || $questionType == QUESTION_TEXT)
            {
                $answer = $_POST['answer'];
            }
            if ($questionType == QUESTION_CHECKBOX)
            {
                $answer = "";
                foreach ($_POST as $key => $value)
                {
                    $answer .=$value." ";
                }
            }
            if ($questionType == QUESTION_MATCH)
            {
                $array = explode("~",$_POST['answer']);
                $rightShuffled = Yii::app()->session['rightShuffledId'];
                $shuffled = Yii::app()->session['shuffledId'];
                $answer = "";
                $i = 0;
                foreach ($array as $item)
                {
                    if ($item == "") continue;
                    $answer.=$rightShuffled[$i]."/".$shuffled[$item]." ";
                    $i++;
                }
            }

            // Сохраняем ответ
            $userAnswer = new UserAnswer();
            $userAnswer->idUserControlMaterial = Yii::app()->session['currentTestGo'];
            $userAnswer->answer = $answer;
            $userAnswer->answerTime = date("Y-m-d H:i:s");
            $userAnswer->idQuestion = Yii::app()->session['questions'][Yii::app()->session['currentQuestion']];
            if (!$userAnswer->save())
            {
                echo "Ошибка! Вопрос не сохранился!";
                echo "<br>Ответ:".$answer;
                echo "<br>Номер вопроса:".$userAnswer->idQuestion;
                return;
            };
        }
        // Проверка на окончание времени
        $testModel = ControlMaterial::model()->findByPk(Yii::app()->session['currentTest']);
        $goModel = UserControlMaterial::model()->findByPk(Yii::app()->session['currentTestGo']);
        $time = (strtotime(date("Y-m-d H:i:s"))-strtotime($goModel->dateStart))/60;
        if ($time > $testModel->dotime)
        {
            $this->redirect($this->createUrl("/controlMaterial/endTest",array("reason" => 2)));
        }
        // Отмечаем вопрос как отвеченный
        $currentQuestion = Yii::app()->session['currentQuestion'];
        $array = Yii::app()->session['flags'];
        $array[$currentQuestion] = false;
        Yii::app()->session['flags'] = $array;
        // Проверяем, есть ли еще неотвеченные вопросы
        $totalQuestions =
        $flag = false;
        foreach (Yii::app()->session['flags'] as $item)
        {
            $flag = $flag || $item;
        }
        if (!$flag)
        {
            $this->redirect('/controlMaterial/endTest');
        }
        $this->nextQuestion();
    }

    public function nextQuestion()
    {
        $i = Yii::app()->session['currentQuestion'];
        do
        {
            $i++;
            if ($i>=count(Yii::app()->session['questions'])) $i = 0;
        } while (!Yii::app()->session['flags'][$i]);

        Yii::app()->session['currentQuestion'] = $i;
        $this->redirect('/controlMaterial/question');
    }

    public function actionSkipQuestion()
    {
        $this->nextQuestion();
    }


    // Окончание теста, выставляем оценку, сохраняем статистику
    public function actionEndTest($reason = 1)
    {
        $currentTestGo = Yii::app()->session['currentTestGo'];
        $questions = array();
        $questionString = "";
        foreach (Yii::app()->session['questions'] as $item)
        {
            $questionString.=$item.',';
            $question = Question::model()->findByPk($item);
            array_push($questions, $question);
        }
        $model = UserControlMaterial::model()->findByPk(Yii::app()->session['currentTestGo']);
        $model->endReason = $reason;
        $model->dateEnd = date("Y-m-d H:i:s");
        $model->questions = $questionString;
        $totalMark = 0;
        $summWeight = 0;
        for ($i = 0; $i<count($questions); $i++)
        {
            $summWeight+= $questions[$i]->weight;
            $userAnswer = UserAnswer::model()->findAll('idUserControlMaterial = :idCur and idQuestion = :idQuestion', array(':idCur' => $currentTestGo,':idQuestion' => $questions[$i]->id));
            if ($userAnswer != null)
            {
                $answerInfo = $questions[$i]->getMark($userAnswer[0]);
                $totalMark+=$answerInfo['mark']*$questions[$i]->weight;
            }
        }
        $model->mark = (int)($totalMark/$summWeight);
        $model->save();
        $this->redirect($this->createUrl('/controlMaterial/viewTestResults', array('id' => $model->id)));
    }

    // Просмотр результатов теста
    public function actionViewTestResults($id)
    {
        $currentTestGo = $id;
        $goTestModel = UserControlMaterial::model()->findByPk($id);
        $questions = array();
        $questionString = "";
        $answers = array();
        foreach (explode(',', $goTestModel->questions) as $item)
        {
            if ($item == "") continue;
            $question = Question::model()->findByPk($item);
            array_push($questions, $question);
        }
        $answerContent = array();
        $mark = array();
        $summWeight = 0;
        for ($i = 0; $i<count($questions); $i++)
        {
            $summWeight+= $questions[$i]->weight;
            $userAnswer = UserAnswer::model()->findAll('idUserControlMaterial = :idCur and idQuestion = :idQuestion', array(':idCur' => $currentTestGo,':idQuestion' => $questions[$i]->id));
            if ($userAnswer != null)
            {
                $answerInfo = $questions[$i]->getMark($userAnswer[0]);
                $mark[] = $answerInfo['mark'];
                $answerContent[] = $answerInfo['answerContent'];
            } else
            {
                $mark[] = 0;
                $answerContent[] = "";
            }
        }
        $this->render('/controlMaterial/testResults', array('questions' => $questions, 'answerContent' => $answerContent, 'mark' => $mark, 'model' => $goTestModel, 'summWeight' => $summWeight));

    }

    /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ControlMaterial');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function loadModel($id)
	{
		$model=ControlMaterial::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function actionEdit($idMaterial)
    {
        $model = $this->loadModel($idMaterial);
        $course = Course::model()->findByPk(Yii::app()->session['currentCourse']);
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => Yii::app()->session['currentCourse']))),
            $model->title => array($this->createUrl("/controlMaterial/edit",array("idMaterial" => $idMaterial))),
        );
        $this->noNeedJquery = true;
        if (isset($_POST['ControlMaterial']))
        {
            $model->attributes=$_POST['ControlMaterial'];
            if($model->save())
                $this->refresh();
        }
        $this->render("editTest",array("model" => $model));
    }


}
