<?php

/**
 * This is the model class for table "tbl_users".
 *
 * The followings are the available columns in table 'tbl_users':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $fio
 * @property string $role
 * @property string $avatar
 */
class User extends CActiveRecord
{

    public $newAvatar = "";
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password', 'length', 'max'=>45),
			array('fio', 'length', 'max'=>100),
			array('role', 'length', 'max'=>15),
			array('avatar', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login, password, fio, role, avatar', 'safe', 'on'=>'search'),
		);
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'groups'=>array(self::MANY_MANY, 'Group', 'tbl_studentsgroups(idStudent,idGroup)'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Логин',
			'password' => 'Пароль',
			'fio' => 'ФИО',
			'role' => 'Роль',
			'avatar' => 'Изображение',
		);
	}

    public function getRussianRole()
    {
        return Yii::app()->params["roles"][$this->role];
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('fio',$this->fio,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('avatar',$this->avatar,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    protected function beforeSave(){
        if ($this->isNewRecord)
        {
            $this->password = md5($this->password);
            mkdir(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->id);
        }
        if (CUploadedFile::getInstance($this,'newAvatar') != "")
        {
            $image = CUploadedFile::getInstance($this,'newAvatar');
            $name = time().".".$image->extensionName;
            $image->saveAs(Yii::getPathOfAlias('webroot.avatars').DIRECTORY_SEPARATOR.$name);
            $this->avatar = $name;
        }
        return parent::beforeSave();
    }

    public function getFiles($category = 'all')
    {
        $result = array();
        $path = Yii::getPathOfAlias("webroot.media.".Yii::app()->user->id);
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file == "." || $file == "..") continue;
                if ($category!='all' && array_search(pathinfo($file, PATHINFO_EXTENSION),Yii::app()->params[$category])===false )
                    continue;
                $temp = array("ext" => pathinfo($file, PATHINFO_EXTENSION), "name" => $file, "path" => $path.DIRECTORY_SEPARATOR.$file);
                array_push($result,$temp);
            }
            closedir($handle);
        }
        return $result;
    }
    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
