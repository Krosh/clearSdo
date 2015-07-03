<?php

/**
 * This is the model class for table "tbl_userscontrolmaterials".
 *
 * The followings are the available columns in table 'tbl_userscontrolmaterials':
 * @property integer $id
 * @property integer $idUser
 * @property integer $idControlMaterial
 * @property string $dateStart
 * @property string $dateEnd
 * @property string $questions
 * @property integer $mark
 * @property integer $endReason
 */
class UserControlMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_userscontrolmaterials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idUser, idControlMaterial, mark, endReason', 'numerical', 'integerOnly'=>true),
			array('dateStart, dateEnd, questions', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idUser, idControlMaterial, dateStart, dateEnd, questions, mark, endReason', 'safe', 'on'=>'search'),
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
			'idUser' => 'Id User',
			'idControlMaterial' => 'Id Control Material',
			'dateStart' => 'Date Start',
			'dateEnd' => 'Date End',
			'questions' => 'Questions',
			'mark' => 'Mark',
			'endReason' => 'End Reason',
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
		$criteria->compare('idUser',$this->idUser);
		$criteria->compare('idControlMaterial',$this->idControlMaterial);
		$criteria->compare('dateStart',$this->dateStart,true);
		$criteria->compare('dateEnd',$this->dateEnd,true);
		$criteria->compare('questions',$this->questions,true);
		$criteria->compare('mark',$this->mark);
		$criteria->compare('endReason',$this->endReason);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserControlMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function setMark($idControlMaterial,$idStudent,$mark)
    {
        UserControlMaterial::model()->deleteAll("idUser = :idUser and idControlMaterial = :idControlMaterial", array(":idUser" => $idStudent, ":idControlMaterial" => $idControlMaterial));
        $model = new UserControlMaterial();
        $model->dateStart = date("Y-m-d H:i:s");
        $model->dateEnd = $model->dateStart;
        $model->idControlMaterial = $idControlMaterial;
        $model->idUser = $idStudent;
        $model->mark = round($mark);
        $model->save();
    }

}
