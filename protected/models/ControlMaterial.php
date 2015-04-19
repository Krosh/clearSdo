<?php

define('CALC_MAX',1);
define('CALC_MIN',2);
define('CALC_AVG',3);
define('CALC_LAST',0);

define('CALC_AUTO',0);
define('CALC_LAUNCH',1);

/**
 * This is the model class for table "tbl_controlmaterials".
 *
 * The followings are the available columns in table 'tbl_controlmaterials':
 * @property integer $id
 * @property string $title
 * @property string $short_title
 * @property integer $dotime
 * @property integer $question_random
 * @property integer $question_show_count
 * @property integer $answer_random
 * @property integer $adaptive
 * @property integer $try_amount
 * @property integer $access
 * @property string $access_date
 * @property integer $show_answers
 * @property integer $is_point
 * @property integer $calc_mode
 * @property integer $idAutor
 * @property integer $show_in_reports
 * @property string $weight
 * @property string $calc_expression
 * @property integer $is_autocalc
 * @property bool $get_files_from_students
 */
class ControlMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $showOnlyNoUsed = false;

	public function tableName()
	{
		return 'tbl_controlmaterials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dotime, question_random, question_show_count, answer_random, adaptive, try_amount, access, show_answers, is_point, calc_mode, idAutor', 'numerical', 'integerOnly'=>true),
			array('title, short_title', 'length', 'max'=>45),
			array('access_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('get_files_from_students,calc_expression,weight,is_autocalc,show_in_reports','safe'),
            array('showOnlyNoUsed, id, title, short_title,  dotime, question_random, question_show_count, answer_random, adaptive, try_amount, access, access_date, show_answers, is_point, calc_mode, idAutor', 'safe', 'on'=>'search'),
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
            'coursesControlMaterials'=>array(self::HAS_MANY, 'CoursesControlMaterial', 'idControlMaterial'),
        );
	}

    public static function getMark($idStudent,$idControlMaterial)
    {
        $controlMaterial = ControlMaterial::model()->findByPk($idControlMaterial);
        $tries = UserControlMaterial::model()->findAll("idControlMaterial = :idMat AND idUser = :idStudent", array(":idMat" => $idControlMaterial, ":idStudent" => $idStudent));
        if (count($tries) <1) return 0;
        if ($controlMaterial->calc_mode == CALC_MIN)
        {
            $mark = 100;
            foreach ($tries as $item)
            {
                $mark = min($mark, $item->mark);
            }
        }
        if ($controlMaterial->calc_mode == CALC_MAX)
        {
            foreach ($tries as $item)
            {
                $mark = max($mark, $item->mark);
            }
        }
        if ($controlMaterial->calc_mode == CALC_AVG)
        {
            $mark = 100;
            foreach ($tries as $item)
            {
                $mark += $item->mark;
            }
            $mark = round($mark/count($tries),0);
        }
        if ($controlMaterial->calc_mode == CALC_LAST)
        {
            $mark = $tries[count($tries)-1]->mark;
        }
        return $mark;
    }

    public static function hasAccess($idControlMaterial)
    {
        $access = AccessControlMaterialGroup::model()->findAll("idControlMaterial = :idControlMaterial", array(":idControlMaterial" => $idControlMaterial));
        if (count($access) > 0)
        {
            $accessModel = $access[0];
            if ($accessModel->access == 1)
            {
                return true;
            }
            if ($accessModel->access == 2)
            {
                return false;
            }
            if ($accessModel->access == 3)
            {
                $startDate = DateHelper::getTimestampFromDateTime($accessModel->startDate);
                $endDate = DateHelper::getTimestampFromDateTime($accessModel->endDate);
                $curDate = DateHelper::getTimestampFromDateTime(date("Y-m-d H:i:s"));
                return ($curDate<=$endDate && $curDate >= $startDate);
            }
            if ($accessModel->access == 4)
            {
                return (ControlMaterial::getMark(Yii::app()->user->getId(),$accessModel->idBeforeTest)>$accessModel->minMark);
            }
        }
        else return true;
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'short_title' => 'Короткое название',
			'dotime' => 'Время выполнения',
			'question_random' => 'Случайный порядок вопросов',
			'question_show_count' => 'Количество предлагаемых вопросов',
			'answer_random' => 'Случайный порядок вариантов ответа',
			'adaptive' => 'Адаптивный тест',
			'try_amount' => 'Количество попыток',
			'access' => 'Доступ',
			'access_date' => 'Дата доступа',
			'show_answers' => 'Показать ответы после прохождения',
			'is_point' => 'Является контрольной точкой',
			'calc_mode' => 'Режим расчета',
            'is_autocalc' => 'Рассчитывать по формуле',
            'weight' => 'Вес',
            'show_in_reports' => 'Включать в отчеты',
			'idAutor' => 'Id Autor',
            'get_files_from_students' => 'Позволять пользователям прикреплять файл',
            'showOnlyNoUsed' => 'Показать только неиспользуемые',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('short_title',$this->short_title,true);
		$criteria->compare('dotime',$this->dotime);
		$criteria->compare('question_random',$this->question_random);
		$criteria->compare('question_show_count',$this->question_show_count);
		$criteria->compare('answer_random',$this->answer_random);
		$criteria->compare('adaptive',$this->adaptive);
		$criteria->compare('try_amount',$this->try_amount);
		$criteria->compare('access',$this->access);
		$criteria->compare('access_date',$this->access_date,true);
		$criteria->compare('show_answers',$this->show_answers);
		$criteria->compare('is_point',$this->is_point);
		$criteria->compare('calc_mode',$this->calc_mode);
		$criteria->compare('idAutor',Yii::app()->user->getId());

        if ($this->showOnlyNoUsed)
        {
            $criteria->with = array(
                'coursesControlMaterials' => array(
                    'joinType' => 'LEFT JOIN',
                    'together' => true,
                ),
            );
            $criteria->addCondition('coursesControlMaterials.idCourse IS NULL');
        }


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getCourses()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idControlMaterial',$this->id);
        $coursesMaterials = CoursesControlMaterial::model()->findAll($criteria);
        $result = "";
        foreach ($coursesMaterials as $item)
        {
            if ($result != "")
                $result.=", ";
            $result .= Course::model()->findByPk($item->idCourse)->title;
        }
        return $result;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ControlMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



}
