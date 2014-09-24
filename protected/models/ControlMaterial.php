<?php

define('CALC_MAX',1);
define('CALC_MIN',2);
define('CALC_AVG',3);
define('CALC_LAST',0);

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
 * @property integer $adaptive-
 * @property integer $try_amount
 * @property integer $access
 * @property string $access_date
 * @property integer $show_answers
 * @property integer $is_point
 * @property integer $calc_mode
 * @property integer $idAutor
 */
class ControlMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
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
			array('title', 'required'),
			array('dotime, question_random, question_show_count, answer_random, adaptive, try_amount, access, show_answers, is_point, calc_mode, idAutor', 'numerical', 'integerOnly'=>true),
			array('title, short_title', 'length', 'max'=>45),
			array('access_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, short_title,  dotime, question_random, question_show_count, answer_random, adaptive, try_amount, access, access_date, show_answers, is_point, calc_mode, idAutor', 'safe', 'on'=>'search'),
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
			'calc_mode' => 'Метод расчета',
			'idAutor' => 'Id Autor',
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
		$criteria->compare('zindex',$this->zindex);
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
		$criteria->compare('idAutor',$this->idAutor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
