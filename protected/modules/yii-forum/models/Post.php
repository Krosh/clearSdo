<?php
define("TABLE_POST",1);
/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property integer $id
 * @property integer $author_id
 * @property integer $thread_id
 * @property integer $editor_id
 * @property string $content
 * @property integer $created
 * @property integer $updated
 *
 * The followings are the available model relations:
 * @property Thread $thread Thread this post lives in
 * @property Forumuser $user User who posted this
 */
class Post extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'post';
    }

    /**
     * @return array primary key of the table
     * */
    public function primaryKey() {
        return array('id');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content', 'required'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'author'=>array(self::BELONGS_TO, 'Forumuser', 'author_id'),
            'thread'=>array(self::BELONGS_TO, 'Thread', 'thread_id'),
            'editor'=>array(self::BELONGS_TO, 'Forumuser', 'editor_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'id' => 'ID',
            'author_id'=>'Автор',
            'thread_id'=>'Тема',
            'editor_id'=>'Редактор',
            'content'=>'Контент',
            'created'=>'Дата создания',
            'updated' => 'Дата обновления',
        ));
    }

    /**
     * Manage the created/updated fields
     */
    public function beforeSave()
    {
        if($this->isNewRecord)
            $this->created = time();
        $this->updated = time();

        return parent::beforeSave();
    }

    public function afterSave()
    {
        return parent::afterSave();
        $searchIndex = SearchIndex::model()->find("idTable = ".TABLE_POST." AND idRecord = ".$this->id);
        if ($searchIndex == null)
        {
            $searchIndex = new SearchIndex();
            $searchIndex->idTable = TABLE_POST;
            $searchIndex->idRecord = $this->id;
        }
        $searchText = strip_tags(mb_strtoupper($this->content, 'UTF-8'));
        $searchText = str_replace(","," ",$searchText);
        $searchText = str_replace("."," ",$searchText);
        $searchText = str_replace("!"," ",$searchText);
        $searchText = str_replace("?"," ",$searchText);
        $searchText = str_replace("&NBSP"," ",$searchText);
        $contentArray = explode(" ",$searchText);
        $resultArray = Yii::app()->morphy->getPseudoRoot($contentArray);
        $text = "";
        foreach ($resultArray as $item)
            $text.= $item[0]." ";
        $searchIndex->content = $text;
        $searchIndex->save();
        return parent::afterSave();
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('content', $this->content, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
