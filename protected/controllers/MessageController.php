<?php

class MessageController extends CController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/messages';
    public $noNeedJquery = false;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
   //         array('application.filters.AccessFilter'),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
        );
    }

    public function actionGetDialogs()
    {
        $sql = "SELECT DISTINCT idRecepient as 'id' FROM `tbl_messages` WHERE idAutor = ".Yii::app()->user->getId()."
                UNION
                SELECT DISTINCT idAutor as 'id' FROM `tbl_messages` WHERE idRecepient = ".Yii::app()->user->getId();
        $command = Yii::app()->db->createCommand($sql);
        $res = $command->queryColumn();
        $items = array();
        foreach ($res as $idUser)
        {
            $user = User::model()->findByPk($idUser);
            $criteria = new CDbCriteria();
            $criteria->addCondition("idAutor = $idUser OR idRecepient = $idUser");
            $criteria->order = "dateSend DESC";
            $criteria->limit = 1;
            $lastMessage = Message::model()->find($criteria);
            $items[] = array("user" => $user, "message" => $lastMessage);
        }
        $this->renderPartial("dialogs", array("items" => $items));

    }

    public function actionGetDialogWithUser()
    {
        $user = User::model()->findByPk($_POST['idUser']);
        $criteria = new CDbCriteria();
        $criteria->addCondition("idAutor = ".Yii::app()->user->getId()." AND idRecepient = ".$_POST['idUser'],'OR');
        $criteria->addCondition("idAutor = ".$_POST['idUser']." AND idRecepient = ".Yii::app()->user->getId(),'OR');
        $criteria->order = "dateSend DESC";
        $criteria->limit = 10;
        $messages = Message::model()->findAll($criteria);
        $this->renderPartial('dialog', array('messages' => array_reverse($messages), 'user' => $user));
    }

    public function actionSendMessage()
    {
        $message = new Message();
        $message->idAutor = Yii::app()->user->getId();
        $message->idRecepient = $_POST["idUser"];
        $message->status = 0;
        $message->text = $_POST["text"];
        $message->save();
    }

    public function actionIndex()
    {
        $this->render('view');

    }

}
