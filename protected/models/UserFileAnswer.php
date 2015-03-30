<?php

/**
 * This is the model class for table "tbl_usersfileanswers".
 *
 * The followings are the available columns in table 'tbl_usersfileanswers':
 * @property integer $id
 * @property integer $idUser
 * @property integer $idControlMaterial
 * @property integer $is_checked
 * @property string $dateAdd
 * @property string $filename
 *
 * The followings are the available model relations:
 * @property User $User
 * @property ControlMaterial $ControlMaterial
 */
class UserFileAnswer extends CActiveRecord
{

    public $fileAttribute = null;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_usersfileanswers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idUser, idControlMaterial, is_checked', 'numerical', 'integerOnly'=>true),
			array('filename', 'length', 'max'=>145),
			array('dateAdd', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idUser, idControlMaterial, is_checked, dateAdd, filename', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'User', 'idUser'),
			'ControlMaterial' => array(self::BELONGS_TO, 'ControlMaterial', 'idControlMaterial'),
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
			'idControlMaterial' => 'Id Control Material',
			'is_checked' => 'Is Checked',
			'dateAdd' => 'Date Add',
			'filename' => 'Filename',
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
		$criteria->compare('idUser',$this->idUser);
		$criteria->compare('idControlMaterial',$this->idControlMaterial);
		$criteria->compare('is_checked',$this->is_checked);
		$criteria->compare('dateAdd',$this->dateAdd,true);
		$criteria->compare('filename',$this->filename,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function beforeSave()
    {
        echo var_dump($_FILES);
        if ($this->isNewRecord)
        {
            if ($this->fileAttribute == null)
            {
                $doc = CUploadedFile::getInstanceByName($this->filename);
            }
            else
                $doc = $this->fileAttribute;
            $this->filename = $doc;
            if (!file_exists(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idUser))
            {
                mkdir(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idUser);
            }
            echo var_dump($doc);
            $name = time().".".strtolower(pathinfo($doc, PATHINFO_EXTENSION));
            if (!$this->filename->saveAs(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idUser.DIRECTORY_SEPARATOR.$name))
                return false;
            $this->filename = $name;
            $this->dateAdd = date("Y-m-d H:i:s");
        }
        return true;
    }

    public function beforeDelete()
    {
        $this->deleteDocument();
//        CoursesMaterial::model()->deleteAll("idMaterial = :id",array("id" => $this->id));
    }

    public function deleteDocument()
    {
        $documentPath=Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idUser.DIRECTORY_SEPARATOR.$this->filename;
        if(is_file($documentPath))
            unlink($documentPath);
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserFileAnswer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
