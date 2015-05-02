<?php

define("ACCESS_RELATION_COMMON",1);
define("ACCESS_RELATION_GROUP",2);
define("ACCESS_RELATION_PERSONAL",3);

define("ACCESS_ALWAYS",1);
define("ACCESS_NO",2);
define("ACCESS_TIME",3);
define("ACCESS_AFTER",4);


/**
 * This is the model class for table "tbl_accesscontrolmaterials".
 *
 * The followings are the available columns in table 'tbl_accesscontrolmaterials':
 * @property integer $id
 * @property integer $type_relation
 * @property integer $idRecord
 * @property integer $idCourse
 * @property integer $idControlMaterial
 * @property integer $accessType
 * @property string $startDate
 * @property string $endDate
 * @property integer $idBeforeMaterial
 * @property integer $minMark
 * @property integer $addTryes
 */
class AccessControlMaterial extends CActiveRecord
{

    public $isCalced = false;
    public $hasAccess = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_accesscontrolmaterials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_relation, idRecord, idCourse, idControlMaterial, accessType, idBeforeMaterial, minMark, addTryes', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type_relation, idRecord, idCourse,  idControlMaterial, accessType, startDate, endDate, idBeforeMaterial, minMark, addTryes', 'safe', 'on'=>'search'),
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
			'type_relation' => 'Type Relation',
			'idRecord' => 'Id Record',
			'idCourse' => 'Id Course',
			'idControlMaterial' => 'Id Control Material',
			'accessType' => 'Access Type',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'idBeforeMaterial' => 'Id Before Material',
			'minMark' => 'Min Mark',
			'addTryes' => 'Add Tryes',
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
		$criteria->compare('type_relation',$this->type_relation);
		$criteria->compare('idRecord',$this->idRecord);
		$criteria->compare('idCourse',$this->idCourse);
		$criteria->compare('idControlMaterial',$this->idControlMaterial);
		$criteria->compare('accessType',$this->accessType);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('idBeforeMaterial',$this->idBeforeMaterial);
		$criteria->compare('minMark',$this->minMark);
		$criteria->compare('addTryes',$this->addTryes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function checkAccess($user)
    {
        if ($this->accessType == 1)
        {
            $this->isCalced = true;
            $this->hasAccess = true;
            return true;
        }
        if ($this->accessType == 2)
        {
            $this->isCalced = true;
            $this->hasAccess = false;
            return $this->hasAccess;
        }
        if ($this->accessType == 3)
        {
            $this->isCalced = true;
            $this->hasAccess = DateHelper::getTimestampFromDateTime($this->startDate)<DateHelper::getTimestampFromDateTime(date("Y-m-d H:i:s")) && (DateHelper::getTimestampFromDateTime($this->endDate)>DateHelper::getTimestampFromDateTime(date("Y-m-d H:i:s")) || $this->endDate = "0000-00-00 00:00:00");
            return $this->hasAccess;
        }
        if ($this->accessType == 4)
        {
            $this->isCalced = true;
            $this->hasAccess = ControlMaterial::model()->getMark($user->id,$this->idBeforeMaterial)>=$this->minMark;
            return $this->hasAccess;
        }
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccessControlMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
