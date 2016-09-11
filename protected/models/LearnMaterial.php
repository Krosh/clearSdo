<?php

/**
 * This is the model class for table "tbl_learnmaterials".
 *
 * The followings are the available columns in table 'tbl_learnmaterials':
 * @property integer $id
 * @property string $path
 * @property string $title
 * @property integer $category
 * @property integer $idAutor
 * @property string $dateAdd
 * @property string $content
 */
class LearnMaterial extends CActiveRecord
{

    public $ext = "";
    public $courses = "";
    public $showOnlyNoUsed = false;
    public $accessInfo;

    public $webinarPassword = "";
    public $webinarIsPublic = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_learnmaterials';
    }

    public function behaviors(){
        return array(
            'logBehavior' => array(
                'class' => 'LogBehavior',
                'tableName' => 'Учебные материалы',
            ),
        );
    }


    public $fileAttribute = null;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category, idAutor', 'numerical', 'integerOnly'=>true),
            array('path, title', 'length', 'max'=>2000),
            array('content', 'length', 'max'=>65535),
            array('ext, courses', 'length', 'max'=>45),
            array('webinarIsPublic,webinarPassword','safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('content, showOnlyNoUsed,ext, courses, id, path, title, category, idAutor', 'safe', 'on'=>'search'),
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
            'courses'=>array(self::MANY_MANY, 'Course', 'tbl_coursesmaterials(idMaterial,idCourse)'),
            'coursesMaterials'=>array(self::HAS_MANY, 'CoursesMaterial', 'idMaterial'),
            // Вместо связи использовать метод LearnMaterial::getMaterialsFromCourse
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'path' => 'Путь к материалу',
            'title' => 'Заголовок',
            'category' => 'Категория',
            'idAutor' => 'Код автора',
            'showOnlyNoUsed' => 'Показать только неиспользуемые',
            "webinarIsPublic" => "Является публичным",
            "webinarPassword" => "Пароль для входа",
            'content' => '',
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
        $criteria->compare('path',$this->path,true);
        $criteria->compare('title',$this->title,true);
        $criteria->addInCondition("category",array(MATERIAL_FILE,MATERIAL_TORRENT,MATERIAL_INBROWSER,MATERIAL_LINK,MATERIAL_WEBINAR));
        $criteria->compare('idAutor',Yii::app()->user->getId());
        if ($this->showOnlyNoUsed)
        {
            $criteria->with = array(
                'coursesMaterials' => array(
                    'joinType' => 'LEFT JOIN',
                    'together' => true,
                ),
            );
            $criteria->addCondition('coursesMaterials.idCourse IS NULL');
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>25
            )
        ));
    }

    static public function getMaterialsFromCourse($idCourse)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idCourse',$idCourse);
        $criteria->order = "zindex";
        $coursesMaterials = CoursesMaterial::model()->findAll($criteria);
        $result = array();
        foreach ($coursesMaterials as $item)
        {
            $result[] = LearnMaterial::model()->findByPk($item->idMaterial);
        }
        return $result;
    }

    public function getAsFile()
    {
        if ($this->category == MATERIAL_FILE || $this->category == MATERIAL_TORRENT)
        {
            return Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor.DIRECTORY_SEPARATOR.$this->path;
        }
        // TODO:: дописать для остальных категорий учебных материалов
    }


    public function getPathToMaterial()
    {
        if ($this->category == MATERIAL_FILE || $this->category == MATERIAL_TORRENT)
        {
            return Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor.DIRECTORY_SEPARATOR.$this->path;
        } else
        {
            return $this->path;
        }
    }

    public function afterFind()
    {
        if ($this->category == MATERIAL_WEBINAR)
        {
            $webinar = Webinar::model()->findByPk($this->content);
            $this->webinarIsPublic = $webinar->isPublic;
            $this->webinarPassword = $webinar->password;
        }
        return parent::afterFind();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LearnMaterial the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord)
        {
            $this->dateAdd = date("Y-m-d H:i:s");
            if ($this->category == MATERIAL_WEBINAR)
            {
                $webinar = new Webinar();
                $webinar->dateStart = $this->path;
                $webinar->status = STATUS_PREPARE;
                $webinar->password = $this->webinarPassword;
                $webinar->isPublic = $this->webinarIsPublic;
                $webinar->save();
                $this->content = $webinar->id;
            }
            if ($this->category == MATERIAL_FILE || $this-> category == MATERIAL_TORRENT)
            {

                if ($this->fileAttribute == null)
                    $doc = CUploadedFile::getInstance($this,'path');
                else
                    $doc = $this->fileAttribute;
                $this->path = $doc;
                if (!file_exists(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor))
                {
                    mkdir(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor);
                }
                $name = time().".".strtolower(pathinfo($doc, PATHINFO_EXTENSION));
                $this->path->saveAs(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor.DIRECTORY_SEPARATOR.$name);
                $this->path = $name;
                if (!is_file(Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor.DIRECTORY_SEPARATOR.$name))
                {
                    return false;
                }
            }
        }
        return parent::beforeSave() ;
    }

    public function beforeDelete()
    {
        if ($this->category == MATERIAL_FILE || $this->category == MATERIAL_TORRENT)
        {
            $this->deleteDocument();
        }
        CoursesMaterial::model()->deleteAll("idMaterial = :id",array("id" => $this->id));
        return parent::beforeDelete() ;
    }

    public function addCommonAccess($idCourse)
    {
        $access = new AccessLearnMaterial();
        $access->idLearnMaterial = $this->id;
        $access->idCourse = $idCourse;
        $access->type_relation = ACCESS_RELATION_COMMON;
        $access->accessType = 1;
        $access->save();
    }

    public function getCommonAccess($idCourse)
    {
        $criteria = new CDbCriteria();
        $criteria->compare("type_relation",1);
        $criteria->compare("idLearnMaterial",$this->id);
        $criteria->compare("idCourse",$idCourse);
        $accessInfo = AccessLearnMaterial::model()->find($criteria);
        if ($accessInfo->accessType == 2)
            return false;
        else
            return true;
    }

    public function hasAccess($idCourse)
    {
        $criteria = new CDbCriteria();
        $criteria->compare("idRecord", Yii::app()->user->getId());
        $criteria->compare("type_relation",3);
        $criteria->compare("idLearnMaterial",$this->id);
        $criteria->compare("idCourse",$idCourse);
        $accessInfo = AccessLearnMaterial::model()->find($criteria);
        if ($accessInfo != null)
        {
            if ($accessInfo->checkAccess(Yii::app()->user->getModel()))
                return $accessInfo;
        }
        $criteria = new CDbCriteria();
        $criteria->compare("idStudent",Yii::app()->user->getId());
        $mas = StudentGroup::model()->findAll($criteria);
        $res = array();
        foreach ($mas as $st)
        {
            $res[] = $st->idGroup;
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition("idRecord", $res);
        $criteria->compare("type_relation",2);
        $criteria->compare("idLearnMaterial",$this->id);
        $criteria->compare("idCourse",$idCourse);
        $accessInfos = AccessLearnMaterial::model()->findAll($criteria);
        if (count($accessInfos) >0)
        {
            for ($i = 0; $i<count($accessInfos); $i++)
            {
                $accessInfo = $accessInfos[$i];
                if ($accessInfo->checkAccess(Yii::app()->user->getModel()))
                    return $accessInfo;
            }
        }
        $criteria = new CDbCriteria();
        $criteria->compare("type_relation",1);
        $criteria->compare("idLearnMaterial",$this->id);
        $criteria->compare("idCourse",$idCourse);
        $accessInfo = AccessLearnMaterial::model()->find($criteria);
        if ($accessInfo == null)
        {
            $this->addCommonAccess($idCourse);
            return $this->getCommonAccess($idCourse);
        } else
        {
            $accessInfo->checkAccess(Yii::app()->user->getModel());
            if ($accessInfo != null)
                return $accessInfo;
            return null;
        }
    }



    public function deleteDocument()
    {
        $documentPath=$this->getPathToMaterial();
        if(is_file($documentPath))
            unlink($documentPath);
    }

    public function getFileSize()
    {
        if (!is_file($this->getPathToMaterial()))
        {
            return '<span style = "color:red">Файл не найден на сервере</span>';
        }
        $size = filesize($this->getPathToMaterial());
        $sizePrefixxes = array(" Б"," Кб", " Мб", " Гб");
        $i = 0;
        do
        {
            $sizeText = $size.$sizePrefixxes[$i];
            $i++;
            $size = floor($size/1024);
        } while ($size>0);
        return $sizeText;
    }

    public function getInfoText($needEditButton = false)
    {
        if ($this->category == MATERIAL_FILE)
        {
            $sizeText = $this->getFileSize();
        }
        if ($this->category == MATERIAL_WEBINAR)
        {
            $webinar = Webinar::model()->findByPk($this->content);
            $webinar->checkOnEnd();
            if ($webinar->status == STATUS_PREPARE && Yii::app()->user->role == ROLE_TEACHER)
            {
                return '<a class = "hrefMaterialWebinar" target = "_blank" href = "#" onclick = "startConference('.$this->id.'); return false"><i class = "fa fa-play"></i> Начать вебинар</a>';
            }
            if ($webinar->status == STATUS_ACTIVE)
            {
                return '<a class = "hrefMaterialWebinar" target = "_blank" href = "'.Yii::app()->controller->createUrl("/webinar/connectToConference", array("idMaterial" => $this->id)).'" target = "_blank"><i class = "fa fa-user-plus"></i> Присоединиться к вебинару</a>';
            }
            if ($webinar->status == STATUS_END)
            {
                $path  = $webinar->getRecordPath();
                if (!$path)
                    return '<span class = "hrefMaterialWebinar" >Вебинар окончен</span>';
                else
                    return '<a class = "hrefMaterialWebinar" href = "'.$path.'" target="_blank"><i class = "fa fa-video-camera"></i> Просмотреть запись</a>';
            }
        }
        if ($this->category == MATERIAL_LINK)
            $sizeText = $this->path;
        return $sizeText;
    }

    public function getEditButton() {
        if ($this->category == MATERIAL_INBROWSER){
            return '<a href="'.Yii::app()->controller->createUrl("/learnMaterial/edit", array("idMaterial" => $this->id)).'"><i class="fa fa-pencil"></i></a>';
        } else {
            return "";
        }
    }

    public function getViewedTitle()
    {
        return str_replace(".".$this->getExtension(),"",$this->title);
    }

    public function getDownloadableName()
    {
        $name = $this->title;
        // TODO:: переделать в более точный алгоритм
        if (strpos($name,".".$this->getExtension()) === false)
            $name.= ".".$this->getExtension();
        return $name;
    }

    public function getExtension()
    {
        if ($this->category==MATERIAL_FILE) {
            return pathinfo($this->path, PATHINFO_EXTENSION);
        }
        return "";
    }

    public function getCourses()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('idMaterial',$this->id);
        $coursesMaterials = CoursesMaterial::model()->findAll($criteria);
        $result = "";
        foreach ($coursesMaterials as $item)
        {
            if ($result != "")
                $result.=", ";
            $result .= Course::model()->findByPk($item->idCourse)->title;
        }
        return $result;
    }

    public function getIconExtension()
    {
        if ($this->category == MATERIAL_LINK)
            return "link";
        if ($this->category == MATERIAL_INBROWSER)
            return "text";
        if ($this->category == MATERIAL_WEBINAR)
            return "webinar";
        $path = "";
        if ($this->category==MATERIAL_FILE) {
            $path = pathinfo($this->path, PATHINFO_EXTENSION);
        }

        switch ($path) {
            case "docx":
                return "file";
            case "txt":
                return "file";
            case "rtf":
                return "file";
            case "doc":
                return "file";
            case "pdf":
                return "pdf";
            case "xls":
                return "excel";
            case "xlsx":
                return "excel";
            case "csv":
                return "excel";
            case "ppt":
                return "presentation";
            case "pptx":
                return "presentation";
            case "zip":
                return "archive";
            case "rar":
                return "archive";
            case "7z":
                return "archive";
            case "tar":
                return "archive";
            case "gz":
                return "archive";
            case "jpg":
                return "image";
            case "jpeg":
                return "image";
            case "bmp":
                return "image";
            case "png":
                return "image";
            case "gif":
                return "image";
            case "avi":
                return "movie";
            case "mpg":
                return "movie";
            case "mp4":
                return "movie";
            case "mov":
                return "movie";
            case "torrent":
                return "torrent";
            default:
                return "no";
        }

    }

}
