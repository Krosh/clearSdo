<?php

/**
 * This is the model class for table "tbl_coursescontrolmaterials".
 *
 * The followings are the available columns in table 'tbl_coursescontrolmaterials':
 * @property integer $id
 * @property integer $idCourse
 * @property integer $idControlMaterial
 * @property integer $zindex
 */
class CoursesControlMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_coursescontrolmaterials';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idCourse, idControlMaterial, zindex', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idCourse, idControlMaterial, zindex', 'safe', 'on'=>'search'),
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
			'idCourse' => 'Id Course',
			'idControlMaterial' => 'Id Control Material',
			'zindex' => 'Zindex',
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
		$criteria->compare('idCourse',$this->idCourse);
		$criteria->compare('idControlMaterial',$this->idControlMaterial);
		$criteria->compare('zindex',$this->zindex);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoursesControlMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public static function getAccessedControlMaterials($idCourse)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idcourse',$idCourse);
        $criteria->order = 'zindex';
        $cMaterials = CoursesControlMaterial::model()->findAll($criteria);
        $result = array();
        foreach ($cMaterials as $item)
        {
            $test = ControlMaterial::model()->findByPk($item->idControlMaterial);
            $access = AccessControlMaterialGroup::model()->find('idControlMaterial = :id AND idGroup IS NULL', array(':id' => $item->id));
            if ($access->access == 2) continue;
            if ($access->access == 3)
            {
                $curDate = new DateTime();
                $startDate = new DateTime($access->startDate);
                $endDate = new DateTime($access->endDate);
                if ($curDate->format("U")<$startDate->format("U") || ($curDate->format("U")>$endDate->format("U") && $endDate->format("U")>0)) continue;
            }
            // TODO:: доступ
            array_push($result,$test);
        }
        return $result;
    }
}
