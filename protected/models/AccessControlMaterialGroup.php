<?php

/**
 * This is the model class for table "tbl_accesscontrolmaterialgroups".
 *
 * The followings are the available columns in table 'tbl_accesscontrolmaterialgroups':
 * @property integer $id
 * @property integer $idControlMaterial
 * @property integer $idGroup
 * @property integer $access
 * @property string $startDate
 * @property string $endDate
 * @property integer $idBeforeTest
 * @property integer $minMark
 */
class AccessControlMaterialGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_accesscontrolmaterialgroups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('idControlMaterial, idGroup, access, idBeforeTest, minMark', 'numerical', 'integerOnly'=>true),
			array('startDate, endDate', 'safe'),
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
			'idControlMaterial' => 'Id Control Material',
			'idGroup' => 'Id Group',
			'access' => 'Доступ',
			'startDate' => 'Дата начала',
			'endDate' => 'Дата окончания',
			'idBeforeTest' => 'После какого теста дать доступ',
			'minMark' => 'Минимальная оценка',
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
		$criteria->compare('idControlMaterial',$this->idControlMaterial);
		$criteria->compare('idGroup',$this->idGroup);
		$criteria->compare('access',$this->access);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('idBeforeTest',$this->idBeforeTest);
		$criteria->compare('minMark',$this->minMark);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccessControlMaterialGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
