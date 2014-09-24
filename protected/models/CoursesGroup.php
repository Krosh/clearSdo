<?php

/**
 * This is the model class for table "tbl_coursesgroups".
 *
 * The followings are the available columns in table 'tbl_coursesgroups':
 * @property integer $id
 * @property integer $idGroup
 * @property integer $idCourse
 * @property integer $idTerm
 */
class CoursesGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_coursesgroups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idGroup, idCourse, idTerm', 'required'),
			array('idGroup, idCourse, idTerm', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idGroup, idCourse, idTerm', 'safe', 'on'=>'search'),
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
      //      'Group'=>array(self::BELONGS_TO, 'Group', 'idGroup'),
      //      'Course'=>array(self::BELONGS_TO, 'Course', 'idCourse'),
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
			'idCourse' => 'Id Course',
			'idTerm' => 'Id Term',
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
		$criteria->compare('idCourse',$this->idCourse);
		$criteria->compare('idTerm',$this->idTerm);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoursesGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function beforeSave()
    {
        $has = CoursesGroup::model()->exists("idGroup=:idGroup AND idCourse=:idCourse AND idTerm=:idTerm",array(':idGroup' => $this->idGroup, ':idCourse' => $this->idCourse, ':idTerm' => $this->idTerm));
        return !$has;
    }
}
