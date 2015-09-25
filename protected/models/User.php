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
 * @property string $lastVisit
 * @property string $curVisit
 * @property string $info
 * @property string $phone
 * @property string $dateChangePassword
 * @property string $email
 * @property string $new_email
 * @property bool $isAvatarModerated
 * @property string $gender
 * @property int $idForumUser
 * @property string $defaultLanguage
 * @property string $activationCache
 */

define("AVATAR_SIZE_NORMAL",0);
define("AVATAR_SIZE_MINI",1);
define("AVATAR_SIZE_MEDIUM",2);
class User extends CActiveRecord
{

    public $newAvatar = "";
    public $showOnlyNoModerated = false;
    public $errorOnSave = "";
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_users';
	}

    public function behaviors(){
        return array(
            'logBehavior' => array(
                'class' => 'LogBehavior',
                'tableName' => 'Пользователи',
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
			array('login, password', 'length', 'max'=>45),
            array('isAvatarModerated', 'length', 'max'=>100),
            array('info', 'length', 'max'=>200),
            array('fio', 'length', 'max'=>100),
			array('role', 'length', 'max'=>15),
			array('avatar', 'length', 'max'=>20),
            array('phone', 'length', 'max'=>20),
            array('new_email,email', 'length', 'max'=>200),
            array('dateChangePassword', 'length', 'max'=>20),
            array('lastVisit', 'length', 'max'=>20),
            array('curVisit', 'length', 'max'=>20),
            array('gender, defaultLanguage', 'length', 'max'=>20),
            array('birthday', 'length', 'max'=>10),
            // The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('new_email,idForumUser, phone, email, showOnlyNoModerated, isAvatarModerated, id, login, password, fio, role, avatar', 'safe', 'on'=>'search'),
		);
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'readedNotices' => array(self::HAS_MANY, 'ReadedNotice', 'idUser'),
            'forumUser' => array(self::HAS_ONE, 'Forumuser', 'siteid'),
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
            'info' => 'Информация',
            'phone' => 'Телефон',
            'email' => 'Электронная почта',
            'isAvatarModerated' => 'Статус проверки аватара',
            'showOnlyNoModerated' => 'Показывать только с немодерированными аватарами',
            'gender' => 'Пол',
            'birthday' => 'Дата рождения',
		);
	}

    public function getRussianRole()
    {
        return Yii::app()->params["roles"][$this->role];
    }

    public function getShortFio()
    {
        $names = explode(" ",$this->fio);
        return $names[0]." ".($names[1] != "" ? substr($names[1],0,2).". " : "").($names[2] != "" ? substr($names[2],0,2)."." : "");
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
        if ($this->showOnlyNoModerated)
            $criteria->compare('isAvatarModerated',0);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'sort'=>array(
                'defaultOrder' => 'fio',
            ),

        ));
	}

    private $defaultAvatarPath = "/img/avatar-default.png";

    public function getAvatarPath($needImageSize = AVATAR_SIZE_NORMAL)
    {
        $names = array(AVATAR_SIZE_NORMAL => ".", AVATAR_SIZE_MINI => "_mini.", AVATAR_SIZE_MEDIUM => "_medium.");
        $avatarPath = $this->avatar;
        $avatarPath = str_replace(".",$names[$needImageSize],$avatarPath);
        if ($this->isAvatarModerated == 1)
            return "/avatars/".$avatarPath;
        else
            return $this->defaultAvatarPath;
    }


    protected function beforeSave(){
        if ($this->isNewRecord)
        {
            $this->password = md5($this->password);
        }
        if ($this->email != "")
        {
            $model = User::model()->findAll("LOWER(email) = LOWER('".$this->email."')");
            if (count($model) > 1 || (count($model) > 0 && ($this->isNewRecord || $model[0]->id !=$this->id)))
            {
                $this->errorOnSave = "Такой e-mail уже зарегистрирован!";
                return false;
            }
            $model = User::model()->findAll("LOWER(new_email) = LOWER('".$this->email."')");
            if (count($model) > 1 || (count($model) > 0 && ($this->isNewRecord || $model[0]->id !=$this->id)))
            {
                $this->errorOnSave = "Такой e-mail уже зарегистрирован!";
                return false;
            }
        }
        $model = User::model()->findAll("LOWER(login) = LOWER('".$this->login."')");
        if (count($model) > 1 || (count($model) > 0 && ($this->isNewRecord || $model[0]->id !=$this->id)))
        {
            $this->errorOnSave = "Такой логин уже зарегистрирован!";
            return false;
        }
        if ($this->login == "")
        {
            $this->errorOnSave = "Нельзя создавать запись с пустым логином!";
            return false;
        }
        if (!$this->isNewRecord) {
            $old = User::model()->findByPk($this->id);
            if ($old->new_email != $this->email && $old->email != $this->email && $this->email != "") {
                $this->sendChangeEmail($old->email);
            }
        } else
        {
            if ($this->email != "")
                $this->sendChangeEmail($this->email);
        }
        if (CUploadedFile::getInstance($this,'newAvatar') != "")
        {
            $this->deleteAvatar();
            $image = CUploadedFile::getInstance($this,'newAvatar');
            $name = time();
            $image->saveAs(Yii::getPathOfAlias('webroot.avatars').DIRECTORY_SEPARATOR.$name.".".$image->extensionName);
            try
            {
                Yii::app()->imageHandler
                    ->load(Yii::getPathOfAlias('webroot.avatars').DIRECTORY_SEPARATOR.$name.".".$image->extensionName)
                    ->thumb(44,44)
                    ->save(Yii::getPathOfAlias('webroot.avatars').DIRECTORY_SEPARATOR.$name."_mini".".".$image->extensionName);
                Yii::app()->imageHandler
                    ->load(Yii::getPathOfAlias('webroot.avatars').DIRECTORY_SEPARATOR.$name.".".$image->extensionName)
                    ->thumb(70,70)
                    ->save(Yii::getPathOfAlias('webroot.avatars').DIRECTORY_SEPARATOR.$name."_medium".".".$image->extensionName);
                $this->avatar = $name.".".$image->extensionName;
            } catch (Exception $e)
            {
                $this->errors[] = $e->getMessage();
                return false;
            }
            if (!Yii::app()->user->isAdmin())
                $this->isAvatarModerated = false;
            else
                $this->isAvatarModerated = true;
        }
        return parent::beforeSave();
    }

    protected function afterSave()
    {
        Yii::import('application.modules.yii-forum.models.*');
        if ($this->forumUser == null)
        {
            $forumUser = new Forumuser();
            $forumUser->siteid = $this->id;
            $forumUser->name = $this->fio;
            $forumUser->save();
        } else{
            $this->forumUser->name = $this->fio;
            $this->forumUser->save();
        }
        if ($this->avatar == "")
            $this->isAvatarModerated = true;
        if (!file_exists(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->id))
            mkdir(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->id);
        return parent::afterSave();
    }

    public function deleteAvatar()
    {
        if ($this->getAvatarPath(AVATAR_SIZE_NORMAL) == $this->defaultAvatarPath)
            return;
        $path = Yii::getPathOfAlias('webroot').$this->getAvatarPath(AVATAR_SIZE_NORMAL);
        if (is_file($path))
            unlink($path);
        $path = Yii::getPathOfAlias('webroot').$this->getAvatarPath(AVATAR_SIZE_MINI);
        if (is_file($path))
            unlink($path);
        $path = Yii::getPathOfAlias('webroot').$this->getAvatarPath(AVATAR_SIZE_MEDIUM);
        if (is_file($path))
            unlink($path);
    }

    public function beforeDelete()
    {
        Message::model()->deleteAll("idAutor = :id OR idRecepient = :id", array(":id" => $this->id));
        Conference::model()->deleteAll("idUser = :id", array(":id" => $this->id));
        AccessControlMaterial::model()->deleteAll("type_relation = :user AND idRecord = :id", array(":id" => $this->id, ":user" => ACCESS_RELATION_PERSONAL));
        CoursesAutor::model()->deleteAll("idAutor = :id", array(":id" => $this->id));
        LearnMaterial::model()->deleteAll("idAutor = :id", array(":id" => $this->id));
        Log::model()->deleteAll("idUser = :id", array(":id" => $this->id));
        StudentGroup::model()->deleteAll("idStudent = :id", array(":id" => $this->id));
        UserControlMaterial::model()->deleteAll("idUser = :id", array(":id" => $this->id));
        UserFileAnswer::model()->deleteAll("idUser = :id", array(":id" => $this->id));
        ReadedNotice::model()->deleteAll("idUser = :id", array(":id" => $this->id));
        $this->deleteAvatar();
        return parent::beforeDelete();
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



    public function getGenderOptions() {
        return array('M' => 'Мужской', 'F' => 'Женский');
    }

    public function activateEmail()
    {
        $cache = md5(rand(1,999999)+"asd");
        $this->activationCache = $cache;
        $this->save();
        $text = "Для восстановления вашего пароля перейдите по ссылке: http://".$_SERVER['SERVER_NAME']."/user/activation?idUser=".$this->id."&cache=".$this->activationCache;
        if(MailHelper::sendMail($this->email,"Восстановление пароля",$text)) {
            return true;
        } else {
            return false;
        }
    }

    public function sendChangeEmail($oldEmail)
    {
        $cache = md5(rand(1,999999)+"asd");
        $this->activationCache = $cache;
        $this->new_email = $this->email;
        $this->email = $oldEmail;
        $this->save();
        $text = "Для изменения адреса электронной почты, перейдите по ссылке: http://".$_SERVER['SERVER_NAME']."/user/setNewEmail?idUser=".$this->id."&cache=".$this->activationCache;
        if(MailHelper::sendMail($this->new_email,"Изменение адрема электронной почты",$text)) {
            return true;
        } else {
            return false;
        }
    }

}
