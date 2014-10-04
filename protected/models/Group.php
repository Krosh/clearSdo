<?php

/**
 * This is the model class for table "tbl_groups".
 *
 * The followings are the available columns in table 'tbl_groups':
 * @property integer $id
 * @property string $Title
 * @property string $id_altstu
 */
class Group extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Title', 'required'),
			array('Title', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, Title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'students'=>array(self::MANY_MANY, 'User', 'tbl_studentsgroups(idGroup,idStudent)'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'Title' => 'Название',
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

        $criteria->compare('Title',$this->Title,true);

        return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function searchAllStudents()
    {
        $arr = array();
        foreach ($this->students as $item)
        {
            array_push($arr,$item->id);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$arr);
        return new CActiveDataProvider('User',array(
            'criteria' => $criteria,
        ));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Group the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getGroupsByCourse($idCourse,$idTerm = -1)
    {
        if ($idTerm != -1)
            $models = CoursesGroup::model()->findAll('idCourse = :idCourse AND idTerm = :idTerm', array(':idCourse' => $idCourse, ':idTerm' => $idTerm));
        else
            $models = CoursesGroup::model()->findAll('idCourse = :idCourse', array(':idCourse' => $idCourse));
        $ids = array();
        foreach ($models as  $item)
        {
            array_push($ids,$item->idGroup);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id",$ids);
        return Group::model()->findAll($criteria);
    }

    public static function getGroupsByAutor($idAutor)
    {
        $courses = CoursesAutor::model()->findAll('idAutor = :idAutor', array(':idAutor' => $idAutor));
        $ids = array();
        foreach ($courses as $item)
        {
            $groups = Group::getGroupsByCourse($item->id);
            foreach ($groups as $curGroup)
            {
                array_push($ids,$curGroup->id);
            }
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id",$ids);
        return Group::model()->findAll($criteria);
    }

}
