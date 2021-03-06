<?php

/**
 * This is the model class for table "tbl_conference".
 *
 * The followings are the available columns in table 'tbl_conference':
 * @property integer $id
 * @property integer $idConference
 * @property integer $idUser
 * @property User $user
 */
class Conference extends CActiveRecord
{
	static public function getNextIdConference()
    {
        return time();
    }

    static public function getUsersFromConference($idConference)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition("idConference = ".$idConference);
        $confs = Conference::model()->findAll($criteria);
        $users = array();
        foreach ($confs as $item)
        {
            $users[$item->idUser] = $item->user;
        }
        return $users;
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_conference';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idConference, idUser', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idConference, idUser', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'idUser'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idConference' => 'Id Conference',
			'idUser' => 'Id User',
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
		$criteria->compare('idConference',$this->idConference);
		$criteria->compare('idUser',$this->idUser);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Conference the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
