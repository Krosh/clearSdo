<?php

define("STATUS_ACTIVE",1);
define("STATUS_INACTIVE",2);
/**
 * This is the model class for table "tbl_groups".
 *
 * The followings are the available columns in table 'tbl_groups':
 * @property integer $id
 * @property integer $status
 * @property integer $form_teaching
 * @property string $Title
 * @property string $id_altstu
 * @property string $facluty
 */
class Group extends CActiveRecord
{


    public static function getStatuses()
    {
        return [1 => "Обучаются", "Закончили"];
    }

    public static function getFormsTeaching()
    {
        return [1 => "Очная", "Вечерняя", "Заочная"];
    }

    public function getStatusAsString()
    {
        return Group::getStatuses()[$this->status];
    }

    public function getFormTeachingAsString()
    {
        return Group::getFormsTeaching()[$this->form_teaching];
    }



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_groups';
	}

    public function behaviors(){
        return array(
            'logBehavior' => array(
                'class' => 'LogBehavior',
                'tableName' => 'Группы',
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
			array('Title', 'required'),
			array('faculty', 'required'),
			array('Title, id_altstu', 'length', 'max'=>20),
            array('form_teaching,status', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('form_teaching,status,id, Title, id_altstu', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'students'=>array(self::MANY_MANY, 'User', 'tbl_studentsgroups(idGroup,idStudent)',"order" => "fio"),
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
			'faculty' => 'Факультет',
            'id_altstu' => 'Код группы на сайте АЛТГТУ',
            'status' => 'Статус',
            'form_teaching' => 'Форма обучения',
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

    public static function getGroupsByAutor($idAutor,$idTerm = -1)
    {
        $courses = CoursesAutor::model()->findAll('idAutor = :idAutor', array(':idAutor' => $idAutor));
        $ids = array();
        foreach ($courses as $item)
        {
            $groups = Group::getGroupsByCourse($item->idCourse,$idTerm);
            foreach ($groups as $curGroup)
            {
                array_push($ids,$curGroup->id);
            }
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id",$ids);
        return Group::model()->findAll($criteria);
    }

    public function beforeDelete()
    {
        CoursesGroup::model()->deleteAll("idGroup = :id", array(":id" => $this->id));
        StudentGroup::model()->deleteAll("idGroup = :id", array(":id" => $this->id));
        AccessControlMaterial::model()->deleteAll("type_relation = :group AND idRecord = :id", array(":group" => ACCESS_RELATION_GROUP, ":id" => $this->id));
        return parent::beforeDelete();
    }

}
