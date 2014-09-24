<?php

/**
 * This is the model class for table "tbl_usersanswers".
 *
 * The followings are the available columns in table 'tbl_usersanswers':
 * @property integer $id
 * @property integer $idUserControlMaterial
 * @property integer $idQuestion
 * @property string $answer
 * @property string $answerTime
 */
class UserAnswer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_usersanswers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idUserControlMaterial, idQuestion', 'numerical', 'integerOnly'=>true),
			array('answer', 'length', 'max'=>45),
			array('answerTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idUserControlMaterial, idQuestion, answer, answerTime', 'safe', 'on'=>'search'),
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
			'idUserControlMaterial' => 'Id User Control Material',
			'idQuestion' => 'Id Question',
			'answer' => 'Answer',
			'answerTime' => 'Answer Time',
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
		$criteria->compare('idUserControlMaterial',$this->idUserControlMaterial);
		$criteria->compare('idQuestion',$this->idQuestion);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('answerTime',$this->answerTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAnswer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
