<?php

/**
 * This is the model class for table "tbl_courses".
 *
 * The followings are the available columns in table 'tbl_courses':
 * @property integer $id
 * @property string $title
 * @property string $discipline
 * @property string $description
 * @property integer $hours
 */
class Course extends CActiveRecord
{
    public $showOnlyNoUsed = false;

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_courses';
	}

    public function behaviors(){
        return array(
            'logBehavior' => array(
                'class' => 'LogBehavior',
                'tableName' => 'Курсы',
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
            array('id, hours', 'numerical', 'integerOnly'=>true),
			array('title, discipline', 'length', 'max'=>45),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('showOnlyNoUsed, id, title, discipline, description, hours', 'safe', 'on'=>'search'),
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
            'coursesGroups'=>array(self::HAS_MANY, 'CoursesGroup', 'idCourse'),
            //      'learnMaterials'=>array(self::MANY_MANY, 'LearnMaterial', 'tbl_coursesmaterials(idCourse,idMaterial)'),
      // Вместо связи использовать метод LearnMaterial::getMaterialsFromCourse
      //      'groups' => array(self::MANY_MANY, 'Group', 'tbl_coursesgroups(idCourse,idGroup)'),
      // Для вывода групп использовать Course::getGroups
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
			'discipline' => 'Дисциплина',
			'description' => 'Описание',
			'hours' => 'Кол-во часов',
            'showOnlyNoUsed' => 'Показывать только неиспользуемые',
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
        $models = CoursesAutor::model()->findAll('idAutor = :idAutor', array(':idAutor' => Yii::app()->user->getId()));
        $ids = array();
        foreach ($models as $item)
        {
            $ids[] = $item->idCourse;
        }

        // @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->addInCondition('t.id',$ids);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('discipline',$this->discipline,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('hours',$this->hours);


        if ($this->showOnlyNoUsed)
        {
            $criteria->with = array(
                'coursesGroups' => array(
                    'joinType' => 'LEFT JOIN',
                    'together' => true,
                ),
            );
            $criteria->addCondition('coursesGroups.idGroup IS NULL');
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    static public function getCoursesByAutor($idAutor, $idTerm)
    {
        $models = CoursesAutor::model()->findAll('idAutor = :idAutor', array(':idAutor' => $idAutor));
        $ids = array();
        foreach ($models as $item)
        {
            if (CoursesGroup::model()->count('idCourse = :idCourse and idTerm = :idTerm',array(':idCourse' => $item->idCourse, ':idTerm' => $idTerm))>0)
                $ids[] = $item->idCourse;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        return Course::model()->findAll($criteria);
    }

    static public function getAutors($idCourse)
    {
        $models = CoursesAutor::model()->findAll('idCourse = :idCourse',array(':idCourse' => $idCourse));
        $ids = array();
        foreach ($models as  $item)
        {
            $ids[] = $item->idAutor;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        return User::model()->findAll($criteria);
    }

    static public function getGroups($idCourse,$idTerm = -1)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idCourse', $idCourse);
        if ($idTerm != -1)
            $criteria->compare('idTerm', $idTerm);
        $models = CoursesGroup::model()->findAll($criteria);
        $ids = array();
        foreach ($models as  $item)
        {
            $ids[] = $item->idGroup;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        $criteria->order = "Title";
        return Group::model()->findAll($criteria);
    }

    static public function getUsers($idCourse, $idTerm = -1)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idCourse', $idCourse);
        if ($idTerm != -1)
            $criteria->compare('idTerm', $idTerm);
        $models = CoursesGroup::model()->findAll($criteria);
        $ids = array();
        foreach ($models as  $item)
        {
            $ids[] = $item->idGroup;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('idGroup',$ids);
        $models = StudentGroup::model()->findAll($criteria);
        $ids = array();
        foreach ($models as $item)
        {
            $ids[] = $item->idStudent;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        $criteria->order = "fio";
        return User::model()->findAll($criteria);
    }

    public function getNameGroups()
    {
        $arr = Course::getGroups($this->id,-1);
        $result = "";
        foreach ($arr as $item)
        {
            if ($result != "")
                $result.=", ";
            $result .= $item->Title;
        }
        return $result;
    }

    static public function getCoursesByGroup($idGroup,$idTerm)
    {
        $models = CoursesGroup::model()->findAll('idGroup = :idGroup AND idTerm = :idTerm', array(':idGroup' => $idGroup, ':idTerm' => $idTerm));
        $idString = '(';
        foreach ($models as  $item)
        {
            $idString.='"'.$item->idCourse.'",';
        }
        $idString = substr($idString,0,strlen($idString)-1).')';
        if ($idString==')') $idString = "(-1)";
        return Course::model()->findAll("id IN $idString");
    }


    public function afterSave()
    {
        $model = new CoursesAutor();
        $model ->isNewRecord = true;
        $model ->idCourse = $this->id;
        $model -> idAutor = Yii::app()->user->id;
        $model -> save();

    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Course the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
