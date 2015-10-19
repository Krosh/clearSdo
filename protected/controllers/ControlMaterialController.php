<?php
class ControlMaterialController extends CController
{
    public $layout='//layouts/full';
    public $breadcrumbs;

    public function filters()
    {
        return array(
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionGetLinks()
    {
        $course = Yii::app()->session['currentCourse'];
        $term = Yii::app()->session['currentTerm'];
        $this->renderPartial("/controlMaterial/links", array("idCourse" => $course, "idTerm" => $term));
    }


    public function actionStartTest($idTest)
    {
        $testModel = $this->loadModel($idTest);
        if (!$testModel->hasAccess(Yii::app()->session['currentCourse']))
            $this->redirect("/");

        $questionsFromDb = Question::getQuestionsByControlMaterial($idTest);
        // В сессию записываем два массива
        // $questionsId - массив с номерами вопросов
        // $isAnswered - массив с флагами, отвечен ли этот вопрос(на случай пропусков вопросов)
        if ($testModel->question_show_count == -1)
            $count = count($questionsFromDb);
        else
            $count = min($testModel->question_show_count, count($questionsFromDb));
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

        $model = new UserControlMaterial();
        $model->idControlMaterial = $idTest;
        $model->idUser = Yii::app()->user->getId();
        $model->dateStart = date("Y-m-d H:i:s");
        $model->save();

        $arr = array();
        for ($i = 0; $i<$count; $i++)
            $arr[$i] = "";
        Yii::app()->session['totalQuestions'] = $count;
        Yii::app()->session['showTimes'] = $arr;
        Yii::app()->session['questions'] = $questions;
        Yii::app()->session['flags'] = $flags;
        Yii::app()->session['currentTest'] = $idTest;
        Yii::app()->session['currentTestGo'] = $model->id;
        Yii::app()->session['currentQuestion'] = 0;

        $this->redirect('/controlMaterial/question');
    }

    // Рендер вопроса
    public function actionQuestion()
    {
        $this->layout = "//layouts/full";
        $i = Yii::app()->session['currentQuestion'];
        $arr = Yii::app()->session['showTimes'];
        if ($arr[$i] == "")
        {
            $arr[$i] = date("Y-m-d H:i:s");
        }
        Yii::app()->session['showTimes'] = $arr;
        // Получениие информации о вопросе и вариантах ответа
        $idQuestion = Yii::app()->session['questions'][$i];
        $question = Question::model()->findByPk($idQuestion);
        $criteria = new CDbCriteria();
        $criteria->compare('question', $idQuestion);
        $criteria->order = 'zindex';
        $answers = Answer::model()->findAll($criteria);
        $test = ControlMaterial::model()->findByPk(Yii::app()->session['currentTest']);
        if ($test->answer_random)
            shuffle($answers);

        // Считаем время
        $currentTestGo = UserControlMaterial::model()->findByPk(Yii::app()->session['currentTestGo']);
        $dateTime = new DateTime($currentTestGo->dateStart);
        $test = ControlMaterial::model()->findByPk($currentTestGo->idControlMaterial);
        $addTimeValue = $test->dotime;
        if ($addTimeValue == "")
            $addTimeValue = 0;
        $dateTime->modify("+$addTimeValue minute");

        // Так как у нас php<5.3 ТО передаем такой костыль
        $this->render('/question/viewQuestion', array('test' => $test, 'question' => $question, 'answers' => $answers, 'endTime' => $dateTime->format("U")));
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
        $userAnswer->showTime = Yii::app()->session['showTimes'][Yii::app()->session['currentQuestion']];
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
        $model->mark = (int)($totalMark/max($summWeight,1));
        $model->save();
        $idGo = Yii::app()->session['currentTestGo'] = -1;
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
        $time = array();
        $summWeight = 0;
        for ($i = 0; $i<count($questions); $i++)
        {
            $summWeight+= $questions[$i]->weight;
            $userAnswer = UserAnswer::model()->find('idUserControlMaterial = :idCur and idQuestion = :idQuestion', array(':idCur' => $currentTestGo,':idQuestion' => $questions[$i]->id));
            if ($userAnswer != null)
            {
                $answerInfo = $questions[$i]->getMark($userAnswer);
                $mark[] = $answerInfo['mark'];
                $time[] = $userAnswer->getFormattedAnswerTime();
                $answerContent[] = $answerInfo['answerContent'];
            } else
            {
                $mark[] = 0;
                $time[] = "";
                $answerContent[] = "Не отвечено";
            }
        }
        $this->render('/controlMaterial/testResults', array('questions' => $questions, 'answerContent' => $answerContent, 'mark' => $mark, 'model' => $goTestModel, 'summWeight' => max($summWeight,1), 'time' => $time));

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

    public function actionFullDeleteMaterial($id)
    {
        $mat = ControlMaterial::model()->findByPk($id);
        if ($mat->idAutor != Yii::app()->user->getId())
            return;
        $criteria = new CDbCriteria();
        $criteria->compare("idControlMaterial",$id);
        CoursesControlMaterial::model()->deleteAll($criteria);
        QuestionsControlMaterial::model()->deleteAll($criteria);
        UserControlMaterial::model()->deleteAll($criteria);
        AccessControlMaterial::model()->deleteAll($criteria);
        ControlMaterial::model()->deleteByPk($id);
    }

    public function actionEdit($idMaterial)
    {
        $needRefresh = false;
        $model = $this->loadModel($idMaterial);
        $course = Course::model()->findByPk(Yii::app()->session['currentCourse']);
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => Yii::app()->session['currentCourse']))),
            $model->title => array($this->createUrl("/controlMaterial/edit",array("idMaterial" => $idMaterial))),
        );
        if (isset($_POST['ControlMaterial']))
        {
            $model->attributes=$_POST['ControlMaterial'];
            if($model->save())
                $needRefresh = true;
        }
        if ($needRefresh)
            $this->refresh();
        $this->render("editTest",array("model" => $model, 'idCourse' => $course->id));
    }

    //TODO:: Этот метод нужно бы перенести в MaterialController, там методы по созданию/удалению материалов
    public function actionCreate($idCourse,$isPoint)
    {
        $model = new ControlMaterial();
        $model->idAutor = Yii::app()->user->getId();
        $model->weight = 1;
        $model->is_point = $isPoint;
        $model->save();
        $model->addToCourse($idCourse);
        $model->addCommonAccess($idCourse);
        $this->redirect($this->createUrl("/controlMaterial/edit", array("idMaterial" => $model->id)));
    }


    public function setMark($idControlMaterial,$idStudent,$mark)
    {
        UserControlMaterial::setMark($idControlMaterial,$idStudent,$mark,true);
    }

    public function actionSetMark()
    {
        $this->setMark($_POST["idControlMaterial"],$_POST["idStudent"],$_POST["mark"]);
    }


    public function actionRecalcMarks()
    {
        echo ControlMaterial::recalcMarks($_POST["idControlMaterial"],$_POST["idGroup"]);
    }

    public function actionGetGroupMarks()
    {
        $group = Group::model()->findAll("Title = :title",array(":title" => $_POST['groupName']));
        $this->renderPartial('groupMarks', array('group' => $group[0],'idControlMaterial' => $_POST['idControlMaterial'], 'needEdit' => true));
    }

    public function actionCalcAndGetGroupMarks()
    {
        $groupName = $_POST['groupName'];
        $group = Group::model()->findAll("Title = :title",array(":title" => $groupName));
        $group = $group[0];
        $idControlMaterial = $_POST['idControlMaterial'];
        $idCourse = Yii::app()->session['currentCourse'];
        $ccm = CoursesControlMaterial::model()->findAll("idCourse = :idCourse AND idControlMaterial = :idControlMaterial", array(":idCourse" => $idCourse, ":idControlMaterial" => $idControlMaterial));
        $ccm = $ccm[0];
        $tests = CoursesControlMaterial::model()->findAll("idCourse = :idCourse AND zindex < :zindex", array(":idCourse" => $idCourse,":zindex" => $ccm->zindex));
        $ids = array();
        foreach ($tests as $test)
        {
            array_push($ids,$test->idControlMaterial);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id",$ids);
        $controlMaterials = ControlMaterial::model()->findAll($criteria);
        foreach ($group->students as $user)
        {
            $weight = 0;
            $mark = 0;
            foreach ($controlMaterials as $controlMaterial)
            {
                $weight += $controlMaterial->weight;
                $mark += $controlMaterial->weight*ControlMaterial::getMark($user->id, $controlMaterial->id);
            }
            UserControlMaterial::model()->deleteAll("idUser = :idUser and idControlMaterial = :idControlMaterial", array(":idUser" => $user->id, ":idControlMaterial" => $idControlMaterial));
            $model = new UserControlMaterial();
            $model->dateStart = date("Y-m-d H:i:s");
            $model->dateEnd = $model->dateStart;
            $model->idControlMaterial = $idControlMaterial;
            $model->idUser = $user->id;
            $model->mark = round($mark / $weight);
            $model->save();
        }
        $this->renderPartial('groupMarks', array('group' => $group,'idControlMaterial' => $idControlMaterial));
    }

    public function actionSaveWeights()
    {
        $controlMaterial = ControlMaterial::model()->findByPk($_POST["idControlMaterial"]);
        $controlMaterial->calc_expression = $_POST["calcExpression"];
        $controlMaterial->save();
    }

    public function actionAddUserFileAnswer()
    {
        if (isset($_FILES['filename']))
        {
            $answer = new UserFileAnswer();
            $answer->idUser = Yii::app()->user->id;
            $answer->idControlMaterial = $_POST['idMaterial'];
            $answer->filename = "filename";
            if ($answer->save())
            {
                UserFileAnswer::model()->deleteAll("idUser = :idUser AND idControlMaterial = :idTest AND id <> :id", array(":idUser" => Yii::app()->user->id, ":idTest" => $_POST['idMaterial'], ":id" => $answer->id));
                echo "success";
            }
        }
        else
            echo "error";
    }

    public function actionDeleteUserFileAnswer()
    {
        UserFileAnswer::model()->deleteAll("idUser = :idUser AND idControlMaterial = :idTest", array(":idUser" => Yii::app()->user->id, ":idTest" => $_POST['idMaterial']));
    }

    public function actionGetUserAnswers($idControlMaterial)
    {
        if(extension_loaded('zip'))
        {
            $labName = ControlMaterial::model()->findByPk($idControlMaterial)->title;
            $zip = new ZipArchive();
            $zip_name = $labName.".zip";
            if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
            {
                return;
            }
            $answers = UserFileAnswer::model()->findAll("idControlMaterial = :id",array(':id' => $idControlMaterial));
            foreach ($answers as $item)
            {
                $userName = StringHelper::translitText($item->User->fio);
                $fileName = $userName.".".strtolower(pathinfo($item->filename, PATHINFO_EXTENSION));
                $zip->addFile($item->getFullPath(),$fileName); // добавляем файлы в zip архив
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

    public function actionGetUserAnswer($idControlMaterial, $idUser)
    {
        if(extension_loaded('zip'))
        {
            $labName = ControlMaterial::model()->findByPk($idControlMaterial)->title;
            $zip = new ZipArchive();
            $zip_name = $labName.".zip";
            if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
            {
                return;
            }
            $answers = UserFileAnswer::model()->findAll("idControlMaterial = :id AND idUser = :idUser",array(':id' => $idControlMaterial, ':idUser' => $idUser));
            foreach ($answers as $item)
            {
                $userName = StringHelper::translitText($item->User->fio);
                $fileName = $userName.".".strtolower(pathinfo($item->filename, PATHINFO_EXTENSION));
                $zip->addFile($item->getFullPath(),$fileName); // добавляем файлы в zip архив
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

    public function actionGetAccessInfo()
    {
        $idControlMaterial = $_POST['idMaterial'];
        $idCourse = $_POST['idCourse'];
        $this->renderPartial("/accessControlMaterial/configAccessForm", array("idControlMaterial" => $idControlMaterial, "idCourse" => $idCourse));

    }

    public function actionUpdateAccessInfo()
    {
        $accessModel = AccessControlMaterial::model()->findByPk($_POST['AccessControlMaterial']['id']);
        $accessModel->attributes = $_POST['AccessControlMaterial'];
        if ($accessModel->idRecord * 1 == 0)
            $accessModel->idRecord = 1;
        $accessModel->startDate = DateHelper::getDatabaseDateFromRussian($_POST['AccessControlMaterial']['startDate'],true);
        $accessModel->endDate = DateHelper::getDatabaseDateFromRussian($_POST['AccessControlMaterial']['endDate'],true);
        $accessModel->save();
    }

    public function actionAddAccessInfo()
    {
        $idControlMaterial = $_POST['idMaterial'];
        $idCourse = $_POST['idCourse'];
        $typeRelation = $_POST['typeRelation'];
        $access = new AccessControlMaterial();
        $access->type_relation = $typeRelation;
        $access->idControlMaterial = $idControlMaterial;
        $access->idCourse = $idCourse;
        $access->save();
    }

    public function actionDeleteAccessInfo()
    {
        AccessControlMaterial::model()->deleteByPk($_POST['id']);
    }

    public function actionStatistic($idMaterial)
    {
        $controlMaterial = ControlMaterial::model()->findByPk($idMaterial);
        // Общая статистика о прохождениях
        $tries = UserControlMaterial::model()->findAll("idControlMaterial = ".$controlMaterial->id);
        $result = array();
        $result['total'] = count($tries);
        $result['bad'] = 0;
        $result['ok'] = 0;
        $result['good'] = 0;
        $result['excellent'] = 0;
        foreach ($tries as $try)
        {
            /**@var UserControlMaterial $try */
            if ($try->mark < 25)
                $result['bad']++;
            elseif ($try->mark < 50)
                $result['ok']++;
            elseif ($try->mark < 75)
                $result['good']++;
            else
                $result['excellent']++;
        }
        // По вопросам
        $questions = Question::getQuestionsByControlMaterial($controlMaterial->id);
        $questionResult = array();
        foreach ($questions as $item)
        {
            $count = 0;
            $mark = 0;
            $time = 0;
            $answers = UserAnswer::model()->findAll("idQuestion = ".$item->id);
            foreach ($answers as $answer)
            {
                $answMark = $item->getMark($answer);
                $mark += $answMark['mark'];
                $count++;
                $time += $answer->getAnswerTime();
            }
            if ($count != 0)
            {
                $mark /= $count;
                $time /= $count;
                $questionResult[] = array('mark' => $mark, 'count' => $count, 'question' => $item, 'time' => $time);
            }
        }
        $this->render("statistic", array("controlMaterial" => $controlMaterial, 'result' => $result, 'questionResult' => $questionResult));
    }
}
