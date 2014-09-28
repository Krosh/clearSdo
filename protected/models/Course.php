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
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_courses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('title, discipline', 'required'),
            array('id, hours', 'numerical', 'integerOnly'=>true),
			array('title, discipline', 'length', 'max'=>45),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, discipline, description, hours', 'safe', 'on'=>'search'),
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('discipline',$this->discipline,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('hours',$this->hours);

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

    static public function getGroups($idCourse,$idTerm)
    {
        $models = CoursesGroup::model()->findAll('idCourse = :idCourse and idTerm = :idTerm',array(':idCourse' => $idCourse, ':idTerm' => $idTerm));
        $ids = array();
        foreach ($models as  $item)
        {
            $ids[] = $item->idGroup;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        return Group::model()->findAll($criteria);
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
