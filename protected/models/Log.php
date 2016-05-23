<?php

/**
 * This is the model class for table "tbl_log".
 *
 * The followings are the available columns in table 'tbl_log':
 * @property integer $id
 * @property integer $idUser
 * @property integer $idRecord
 * @property string $tableName
 * @property integer $idAction
 * @property string $dateAction
 */
class Log extends CActiveRecord
{

    static public $actionNames = array(LOG_CREATE => "Создание", LOG_UPDATE => "Изменение", LOG_DELETE => "Удаление");
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idUser, idAction, idRecord', 'numerical', 'integerOnly'=>true),
			array('tableName', 'length', 'max'=>45),
			array('dateAction', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idUser, tableName, idAction, dateAction, idRecord', 'safe', 'on'=>'search'),
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
			'tableName' => 'Tablename',
			'idAction' => 'Id Action',
			'dateAction' => 'Date Action',
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
        if ($this->idUser != "")
        {
            $userCriteria = new CDbCriteria();
            $userCriteria->addSearchCondition("fio",$this->idUser);
            $res = User::model()->findAll($userCriteria);
            $arr = array();
            foreach ($res as $item)
            {
                $arr[] = $item->id;
            }
            $criteria->addInCondition('idUser',$arr);
        }
		$criteria->compare('tableName',$this->tableName,true);
		$criteria->compare('idAction',$this->idAction);
		$criteria->compare('dateAction',$this->dateAction,true);
        $criteria->order = "dateAction DESC";


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>25
            )
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getUserName()
    {
        $user = User::model()->findByPk($this->idUser);
        return $user->fio;
    }

}
