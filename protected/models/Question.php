<?php

define("QUESTION_RADIO", 1);
define("QUESTION_CHECKBOX", 2);
define("QUESTION_NUMERIC", 3);
define("QUESTION_TEXT", 4);
define("QUESTION_MATCH", 5);

/**
 * This is the model class for table "tbl_question".
 *
 * The followings are the available columns in table 'tbl_question':
 * @property integer $id
 * @property integer $type
 * @property string $content
 * @property integer $fee
 * @property integer $random_answer
 * @property integer $weight
 */
class Question extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_question';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
//			array('type, content, fee, weight', 'required'),
            array('type, fee, random_answer, weight', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, content, fee, random_answer, weight', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }


    public static function getQuestionsByControlMaterial($id)
    {
        // EROOORRRR!!!
        // TODO:: переделать подобную фигню в нормальные запросы к связанным таблицам
        $criteria = new CDbCriteria();
        $criteria->compare('idControlMaterial',$id);
        $criteria->order = 'zindex';
        $models = QuestionsControlMaterial::model()->findAll($criteria);
        $idString = array();
        foreach ($models as $item)
        {
            array_push($idString,$item->idQuestion);
        }
        $result = array();
        foreach ($idString as $item)
        {
            array_push($result, Question::model()->find('id = :item', array(':item' => $item)));
            echo $item->id;
        }
        return $result;
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => 'Тип вопроса',
            'content' => 'Содержимое вопроса',
            'fee' => 'Штраф',
            'random_answer' => 'Случайный порядок ответов',
            'weight' => 'Вес',
        );
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('type',$this->type);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('fee',$this->fee);
        $criteria->compare('random_answer',$this->random_answer);
        $criteria->compare('weight',$this->weight);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    public function actionChangeQuestionsZindex($id)
    {
        QuestionsControlMaterial::model()->deleteAll('idControlMaterial = :idControlMaterial',array(':idControlMaterial' => $id));
        foreach ($_POST as $key => $value)
        {
            if (strpos($key, 'material') === false) continue;
            $matId = str_replace('material','',$key);
            $courseMaterial = new CoursesMaterial();
            $courseMaterial->idCourse = $id;
            $courseMaterial->idMaterial = $matId;
            $courseMaterial->zindex = $value;
            $courseMaterial->save();
        }
        Yii::app()->user->setFlash('message','Порядок материалов изменен');
        $this->redirect($this->createUrl('/site/editCourse', array('id' => $id)));
    }

    public function getMark($userAnswer)
    {
        $mark = 0;
        // TODO:: выделить код проверки на правильный ответ в отдельный метод
        if ($this->type == QUESTION_RADIO)
        {
            $idAnswer = $userAnswer->answer;
            $answer = Answer::model()->findByPk($idAnswer);
            $answerContent = str_replace("~","",$answer->content);
            if ($answer->right>0)
            {
                $isRightAnswer = true;
                $mark = 100;
            }
            else
            {
                $isRightAnswer = false;
                $mark = 0;
            }
        }
        if ($this->type == QUESTION_CHECKBOX)
        {
            $ids = explode(' ',$userAnswer->answer);
            $rightCount = 0;
            $allCount = 0;
            $answerContent = "";
            foreach ($ids as $itemId)
            {
                if ($itemId == "") continue;
                $curAnswer = Answer::model()->findByPk($itemId);
                $answerContent.=str_replace("~","",$curAnswer->content).", ";
                if ($curAnswer->right>0) $rightCount++;
                $allCount++;
            }
            $answerContent = substr($answerContent, 0,strlen($answerContent) -2);
            // TODO:: это переделать в получение COUNT
            $needAnswers = Answer::model()->findAll('question = :idQuestion AND `right` > 0', array(':idQuestion' => $this->id));
            $needAnswerCount = count($needAnswers);
            // Формула для нахождения оценки -
            // О = Штр/всегоПрав; Штр = Всего - прав + | всегоПрав - прав|;
            $wrongCount = $allCount - $rightCount + abs($needAnswerCount - $rightCount);
            if ($needAnswerCount >0)
                $mark = max(round(100 * (1 - $wrongCount/$needAnswerCount)),0);
            else
                $mark = 0;
            if ($mark == 100) $isRightAnswer = true;
            if ($mark == 0) $isRightAnswer = false;
            if ($mark< 100 && $mark>0) $isRightAnswer = 2; // 2 - наполовину верно;
        }
        if ($this->type == QUESTION_NUMERIC || $this->type == QUESTION_TEXT)
        {
            $answer = Answer::model()->find('question = :id', array(':id' => $this->id));
            $text = str_replace("~","",$answer->content);
            $mark = 0;
            $isRightAnswer = false;

            $string2=utf8_decode($userAnswer->answer);
            $string2=strtoupper($string2);
            $string2=utf8_encode($string2);

            $arrs = explode("|",$text);
            foreach ($arrs as $item)
            {
                $string1=utf8_decode($item);
                $string1=strtoupper($string1);
                $string1=utf8_encode($string1);

                if ($string2 == $string1)
                {
                    $mark = 100;
                    $isRightAnswer = true;
                }
            }
            $answerContent = $userAnswer->answer;
        }
        if ($this->type == QUESTION_MATCH)
        {
            $answerContent = "";
            $rightCount = 0;
            $allCount = 0;
            $ids = explode(" ",$userAnswer->answer);
            foreach ($ids as $item)
            {
                if ($item == "") continue;
                $t = explode('/',$item);
                $answer = Answer::model()->findByPk($t[0]);
                if ($answer->right > 0)
                {
                    $allCount++;
                    if ($t[0] == $t[1]) $rightCount++;
                }
                $leftAnswer = Answer::model()->findByPk($t[0]);
                $rightAnswer = Answer::model()->findByPk($t[1]);
                $m1 = explode("~",$leftAnswer->content);
                $m2 = explode("~",$rightAnswer->content);
                if ($answer->right >0 && $m1[0] != "")
                    $answerContent.="<br>".$m1[0]." - ".$m2[1];

            }
            //echo $answers[$i]->answer;
            // echo $allCount;
            // return;
            if ($allCount >0)
                $mark = $rightCount*100/$allCount;
            else
                $mark = 0;
        }
        return array('isRightAnswer' => $isRightAnswer, 'mark' => $mark, 'answerContent' => $answerContent);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Question the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
