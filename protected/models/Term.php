<?php

/**
 * This is the model class for table "tbl_terms".
 *
 * The followings are the available columns in table 'tbl_terms':
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 */
class Term extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_terms';
	}

    public function behaviors(){
        return array(
            'logBehavior' => array(
                'class' => 'LogBehavior',
                'tableName' => 'Периоды',
            ),
        );
    }


    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'length', 'max'=>45),
			array('start_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, start_date, end_date', 'safe', 'on'=>'search'),
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
			'start_date' => 'Дата начала',
			'end_date' => 'Дата окончания',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder'=>array(
                    'title'=>CSort::SORT_ASC,
                )
            )
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Term the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getTermsByAutor($idAutor)
    {
//        $courses = Course::getCoursesByAutor($idAutor);
//        $ids = array();
//        foreach ($courses as $item)
//        {
//            array_push($ids,$item->idTerm);
//        }
        $criteria = new CDbCriteria();
//        $criteria->addInCondition("id",$ids);
        return Term::model()->findAll($criteria);
    }

    public function getNumOfWeek()
    {
        $dayDiff = (strtotime(date("Y-m-d"))-strtotime($this->start_date))/(24*60*60);
        $dayDiff = $dayDiff % 14;
        if ($dayDiff>=7) return 1; else return 0;
    }

    public function canDelete()
    {
        return !CoursesGroup::model()->exists("idTerm = :idTerm", array(":idTerm" => $this->id));
    }

    public function beforeSave()
    {
        $this->start_date = DateHelper::getDatabaseDateFromRussian($this->start_date);
        $this->end_date = DateHelper::getDatabaseDateFromRussian($this->end_date);
        return parent::beforeSave();
    }

}
