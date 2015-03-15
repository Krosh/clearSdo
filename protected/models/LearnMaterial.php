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
 */
class LearnMaterial extends CActiveRecord
{

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_learnmaterials';
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
			array('path', 'length', 'max'=>200),
			array('title', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, path, title, category, idAutor', 'safe', 'on'=>'search'),
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
//            'courses'=>array(self::MANY_MANY, 'Course', 'tbl_coursesmaterials(idMaterial,idCourse)'),
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
		$criteria->compare('category',$this->category);
		$criteria->compare('idAutor',$this->idAutor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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


    public function getPathToMaterial()
    {
        if ($this->category == 1 || $this->category == 3)
        {
            return Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor.DIRECTORY_SEPARATOR.$this->path;
        } else
        {
            return $this->path;
        }
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
        if ($this->isNewRecord && ($this->category == MATERIAL_FILE || $this-> category == MATERIAL_TORRENT))
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
        }
        return true;
    }

    public function beforeDelete()
    {
        if ($this->category == MATERIAL_FILE || $this-> category == MATERIAL_TORRENT)
        {
            $this->deleteDocument();
        }
        CoursesMaterial::model()->deleteAll("idMaterial = :id",array("id" => $this->id));
    }

    public function deleteDocument()
    {
        $documentPath=Yii::getPathOfAlias('webroot.media').DIRECTORY_SEPARATOR.$this->idAutor.DIRECTORY_SEPARATOR.$this->path;
        if(is_file($documentPath))
            unlink($documentPath);
    }

    public function getFileSize()
    {
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

    public function getExtension()
    {
        if ($this->category==MATERIAL_FILE) {
            return pathinfo($this->path, PATHINFO_EXTENSION);
        }
        return "";
    }

    public function getIconExtension()
    {
        $path = "";
        if ($this->category==MATERIAL_FILE) {
            $path = pathinfo($this->path, PATHINFO_EXTENSION);
        }

        switch ($path) {
            case "docx":
                $f = "file";
                break;
            case "txt":
                $f = "file";
                break;
            case "rtf":
                $f = "file";
                break;
            case "doc":
                $f = "file";
                break;
            case "pdf":
                $f = "pdf";
                break;
            case "xls":
                $f = "excel";
                break;
            case "xlsx":
                $f = "excel";
                break;
            case "csv":
                $f = "excel";
                break;
            case "ppt":
                $f = "presentation";
                break;
            case "pptx":
                $f = "presentation";
                break;
            case "zip":
                $f = "archive";
                break;
            case "rar":
                $f = "archive";
                break;
            case "7z":
                $f = "archive";
                break;
            case "tar":
                $f = "archive";
                break;
            case "gz":
                $f = "archive";
                break;
            case "jpg":
                $f = "image";
                break;
            case "jpeg":
                $f = "image";
                break;
            case "bmp":
                $f = "image";
                break;
            case "png":
                $f = "image";
                break;
            case "gif":
                $f = "image";
                break;
            case "avi":
                $f = "movie";
                break;
            case "mpg":
                $f = "movie";
                break;
            case "mp4":
                $f = "movie";
                break;
            case "mov":
                $f = "movie";
                break;
            case "torrent":
                $f = "torrent";
                break;
            default:
                $f = "no";
        }
        return $f;

    }

}
