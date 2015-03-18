<?php

/**
 * This is the model class for table "tbl_timetable".
 *
 * The followings are the available columns in table 'tbl_timetable':
 * @property integer $id
 * @property integer $idGroup
 * @property integer $numWeek
 * @property integer $day
 * @property string $time
 * @property string $name
 * @property string $room
 * @property string $teacher
 */
class Timetable extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_timetable';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idGroup, numWeek, day', 'numerical', 'integerOnly'=>true),
			array('time', 'length', 'max'=>20),
			array('room', 'length', 'max'=>15),
			array('teacher', 'length', 'max'=>70),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idGroup, numWeek, day, time, name, room, teacher', 'safe', 'on'=>'search'),
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
			'idGroup' => 'Id Group',
			'numWeek' => 'Num Week',
			'day' => 'Day',
			'time' => 'Time',
			'name' => 'Name',
			'room' => 'Room',
			'teacher' => 'Teacher',
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
		$criteria->compare('idGroup',$this->idGroup);
		$criteria->compare('numWeek',$this->numWeek);
		$criteria->compare('day',$this->day);
		$criteria->compare('time',$this->time,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('room',$this->room,true);
		$criteria->compare('teacher',$this->teacher,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Timetable the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
