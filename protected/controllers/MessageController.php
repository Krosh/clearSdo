<?php

class MessageController extends CController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/standart_with_messages';

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
        $startDialog = $_POST["startDialog"];
        $sql = "SELECT DISTINCT idRecepient as 'id' FROM `tbl_messages` WHERE idAutor = ".Yii::app()->user->getId()."
                UNION
                SELECT DISTINCT idAutor as 'id' FROM `tbl_messages` WHERE idRecepient = ".Yii::app()->user->getId();
        $command = Yii::app()->db->createCommand($sql);
        $res = $command->queryColumn();
        $items = array();
        foreach ($res as $idUser)
        {
            $user = User::model()->findByPk($idUser);
            if ($user == null)
                continue;
            $criteria = new CDbCriteria();
            $criteria->addCondition("idAutor = ".Yii::app()->user->getId()." AND idRecepient = ".$idUser,'OR');
            $criteria->addCondition("idAutor = ".$idUser." AND idRecepient = ".Yii::app()->user->getId(),'OR');
            $criteria->order = "dateSend DESC";
            $criteria->limit = 1;
            $lastMessage = Message::model()->find($criteria);
            $criteria = new CDbCriteria();
            $criteria->addCondition("idRecepient = ".Yii::app()->user->id." AND idAutor = $idUser AND status = 0");
            $count = Message::model()->count($criteria);
            $items[] = array("user" => $user, "message" => $lastMessage, "hasNonReadable" => $count>0);
        }
        for ($i = 0; $i<count($items); $i++)
            for ($j = $i+1; $j<count($items); $j++)
            {
                if ((strtotime($items[$i]["message"]->dateSend) < strtotime($items[$j]["message"]->dateSend) && $items[$i]["user"]->id != $startDialog) || ($startDialog!= -1 && $items[$j]["user"]->id == $startDialog))
                {
                    $t = $items[$i];
                    $items[$i] = $items[$j];
                    $items[$j] = $t;
                }
            }
        if ($startDialog>-1 && (count($items) == 0 || $items[0]["user"]->id != $startDialog))
        {
            $user = User::model()->findByPk($startDialog);
            if ($user != null)
            {$criteria = new CDbCriteria();
                $criteria->addCondition("idAutor = ".Yii::app()->user->getId()." AND idRecepient = ".$startDialog,'OR');
                $criteria->addCondition("idAutor = ".$startDialog." AND idRecepient = ".Yii::app()->user->getId(),'OR');
                $criteria->order = "dateSend DESC";
                $criteria->limit = 1;
                $lastMessage = Message::model()->find($criteria);
                $criteria = new CDbCriteria();
                $criteria->addCondition("(idAutor = $startDialog) AND (status = 0)");
                $count = Message::model()->count($criteria);
                $item = array("user" => $user, "message" => $lastMessage, "hasNonReadable" => $count>0);
                $items = array_merge(array($item),$items);
            }
        }
        if (count($items)>0)
            $idStartDialog = $items[0]["user"]->id;
        else
            $idStartDialog = -1;
        $text = $this->renderPartial("dialogs", array("items" => $items),true);
        echo json_encode(array("text" => $text, "idDialog" => $idStartDialog));

    }

    public function actionGetDialogWithUser()
    {
        $user = User::model()->findByPk($_POST['idUser']);
        if ($user == null)
        {
            echo json_encode(array("text" => ""));
            return;
        }
        $criteria = new CDbCriteria();
        $criteria->addCondition("idAutor = ".Yii::app()->user->getId()." AND idRecepient = ".$_POST['idUser'],'OR');
        $criteria->addCondition("idAutor = ".$_POST['idUser']." AND idRecepient = ".Yii::app()->user->getId(),'OR');
        $criteria->order = "dateSend DESC";
        $criteria->limit = 10;
        $messages = Message::model()->findAll($criteria);
        $text = $this->renderPartial('dialog', array('messages' => array_reverse($messages), 'user' => $user),true);

        echo json_encode(array("text" => $text));
    }

    public function actionSendMessage()
    {
        $criteria = new CDbCriteria();
        $idUser = $_POST["idUser"];
        $criteria->addCondition("idAutor = ".Yii::app()->user->getId()." AND idRecepient = ".$idUser,'OR');
        $criteria->addCondition("idAutor = ".$idUser." AND idRecepient = ".Yii::app()->user->getId(),'OR');
        $criteria->order = "dateSend DESC";
        $criteria->limit = 1;
        $message = Message::model()->find($criteria);
        $timeDiffToCreateNewMessage = 60*60;
        // TODO:: вынести в настройки
        if ($message != null && $message->idRecepient == $idUser && strtotime(date("Y-m-d H:i:s")) - strtotime($message->dateSend) < $timeDiffToCreateNewMessage)
        {
            $message->status = 0;
            $message->text.= "<br>".$_POST["text"];
            $message->save();
        } else
        {
            $message = new Message();
            $message->idAutor = Yii::app()->user->getId();
            $message->idRecepient = $idUser;
            $message->status = 0;
            $message->text = $_POST["text"];
            $message->save();
        }
    }

    public function actionIndex($startDialog = -1)
    {
        $this->render('view', array("startDialog" => $startDialog));

    }

}
