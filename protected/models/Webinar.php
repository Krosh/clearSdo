<?php

/**
 * This is the model class for table "tbl_webinars".
 *
 * The followings are the available columns in table 'tbl_webinars':
 * @property integer $id
 * @property string $idWebinar
 * @property string $dateStart
 * @property integer $status
 */
class Webinar extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_webinars';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('idWebinar', 'length', 'max'=>45),
			array('dateStart', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idWebinar, dateStart, status', 'safe', 'on'=>'search'),
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
			'idWebinar' => 'Id Webinar',
			'dateStart' => 'Date Start',
			'status' => 'Status',
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
		$criteria->compare('idWebinar',$this->idWebinar,true);
		$criteria->compare('dateStart',$this->dateStart,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Webinar the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function checkOnEnd()
    {
        if ($this->status != STATUS_ACTIVE)
            return;
        // Если получение информации выдает ошибку, значит, вебинар уже закончен
        // И мы меняем ему статус
        $bbb=Yii::app()->bigbluebutton;
        $bbb->attendeePW=Yii::app()->params['attendeePW'];
        $bbb->moderatorPW=Yii::app()->params['moderatorPW'];
        try
        {
            $meeting=$bbb->getMeetingForUser(
                $this->idWebinar,
                "Test");
        }
        catch (BigBlueButtonException $ex)
        {
            $this->status = STATUS_END;
            $this->save();
        }
    }



    public function getRecordPath()
    {
        if ($this->status != STATUS_END)
            return false;
        try
        {
            $bbb=Yii::app()->bigbluebutton;
            $bbb->attendeePW=Yii::app()->params['attendeePW'];
            $bbb->moderatorPW=Yii::app()->params['moderatorPW'];
            $result = $bbb->getRecordings($this->idWebinar);
            if ($result['messageKey'] != 'noRecordings')
                return $result['recordings']["recording"]['playback']['format']['url'];
            else
                return false;
        } catch (Exception $ex)
        {
            return false;
        }
    }

}
