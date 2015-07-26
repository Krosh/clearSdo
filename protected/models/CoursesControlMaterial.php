<?php

/**
 * This is the model class for table "tbl_coursescontrolmaterials".
 *
 * The followings are the available columns in table 'tbl_coursescontrolmaterials':
 * @property integer $id
 * @property integer $idCourse
 * @property integer $idControlMaterial
 * @property integer $zindex
 * @property string $dateAdd
 * @property string $dateAction

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
            array('dateAction, dateAdd, id, idCourse, idControlMaterial, zindex', 'safe', 'on'=>'search'),
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
            'controlMaterial' => array(self::BELONGS_TO, 'ControlMaterial', 'idControlMaterial'),
            'course' => array(self::BELONGS_TO, 'Course', 'idCourse'),
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

    public static function getAllControlMaterials($idCourse,$idExceptionTest = -1)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idcourse',$idCourse);
        if ($idExceptionTest != -1)
        {
            $criteria->addCondition("idControlMaterial != ".$idExceptionTest);
        }
        $criteria->order = 'zindex';
        $cMaterials = CoursesControlMaterial::model()->findAll($criteria);
        $result = array();
        foreach ($cMaterials as $item)
        {
            $test = ControlMaterial::model()->findByPk($item->idControlMaterial);
            array_push($result,$test);
        }
        return $result;
    }


    public static function getAccessedControlMaterials($idCourse)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idCourse',$idCourse);
        $criteria->order = 'zindex';
        $cMaterials = CoursesControlMaterial::model()->findAll($criteria);
        $result = array();
        foreach ($cMaterials as $item)
        {
            $test = ControlMaterial::model()->findByPk($item->idControlMaterial);
            $test->accessInfo = $test->hasAccess($idCourse);
            array_push($result,$test);
        }
        return $result;
    }
}
