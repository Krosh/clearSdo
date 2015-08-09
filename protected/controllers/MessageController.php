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
            array('deny',
                'users'=>array('*'),
            ),
        );
    }


    public function actionGetDialogs()
    {
        $startDialog = $_POST["startDialog"];
        $sql = "SELECT DISTINCT idRecepient as 'id', 0 as 'isConference' FROM `tbl_messages` WHERE idAutor = ".Yii::app()->user->getId()." AND isConference <> 1
                UNION
                SELECT DISTINCT idAutor as 'id', 0 as 'isConference' FROM `tbl_messages` WHERE idRecepient = ".Yii::app()->user->getId()." AND isConference <> 1
                UNION
                SELECT DISTINCT idRecepient as 'id', 1 as 'isConference' FROM `tbl_messages` mes INNER JOIN `tbl_conference` conf ON mes.idRecepient = conf.idConference WHERE idUser = ".Yii::app()->user->getId()." AND isConference = 1
                ";
        $command = Yii::app()->db->createCommand($sql);
        $res = $command->queryAll();
        $items = array();
        foreach ($res as $row)
        {
            $idUser = $row['id'];
            if ($row['isConference'] == 0)
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
                $criteria->addCondition("idRecepient = ".Yii::app()->user->id." AND idAutor = $idUser AND status = 0 AND isService <> 1");
                $count = Message::model()->count($criteria);
                $items[] = array("user" => $user, "message" => $lastMessage, "hasNonReadable" => $count>0, "isConf" => 0, "idUser" => $idUser);
            } else
            {
                $criteria = new CDbCriteria();
                $criteria->addCondition("idConference = ".$idUser);
                $confs = Conference::model()->findAll($criteria);
                $users = array();
                foreach ($confs as $item)
                {
                    $users[] = $item->user;
                }
                $criteria = new CDbCriteria();
                $criteria->addCondition("idRecepient = ".$idUser." AND isConference = 1");
                $criteria->order = "dateSend DESC";
                $criteria->limit = 1;
                $lastMessage = Message::model()->find($criteria);
                $criteria = new CDbCriteria();
                $criteria->addCondition("idAutor <> ".Yii::app()->user->id." AND idRecepient = $idUser AND status = 0 AND isService <> 1 AND isConference = 1");
                $count = Message::model()->count($criteria);
                $items[] = array("user" => $users, "message" => $lastMessage, "hasNonReadable" => $count>0, "isConf" => 1, "idUser" => $idUser);
            }
        }
        for ($i = 0; $i<count($items); $i++)
            for ($j = $i+1; $j<count($items); $j++)
            {
                if ((strtotime($items[$i]["message"]->dateSend) < strtotime($items[$j]["message"]->dateSend)) || (!$items[$j]['isConf'] && $items[$j]["user"]->id == $startDialog))
                {
                    $t = $items[$i];
                    $items[$i] = $items[$j];
                    $items[$j] = $t;
                }
            }
        $MAX_DIALOGS_COUNT = 4;
        $mas = $items;
        $items = array();
        for ($i = 0; $i < min(count($mas),$MAX_DIALOGS_COUNT); $i++)
        {
            $items[] = $mas[$i];
        }
        if ($startDialog>-1 && (count($items) == 0 || $items[0]['isConf'] || $items[0]["user"]->id != $startDialog))
        {
            $user = User::model()->findByPk($startDialog);
            if ($user != null)
            {
                $criteria = new CDbCriteria();
                $criteria->addCondition("idAutor = ".Yii::app()->user->getId()." AND idRecepient = ".$startDialog,'OR');
                $criteria->addCondition("idAutor = ".$startDialog." AND idRecepient = ".Yii::app()->user->getId(),'OR');
                $criteria->order = "dateSend DESC";
                $criteria->limit = 1;
                $lastMessage = Message::model()->find($criteria);
                $criteria = new CDbCriteria();
                $criteria->addCondition("(idAutor = $startDialog) AND (status = 0)");
                $count = Message::model()->count($criteria);
                $item = array("user" => $user, "message" => $lastMessage, "hasNonReadable" => $count>0, "isConf" => 0, "idUser" => $user->id);
                $items = array_merge(array($item),$items);
            }
        }
        if (count($items)>0)
        {
            $isConf = $items[0]["isConf"];
            $idStartDialog = $items[0]["idUser"];
        }
        else
        {
            $isConf = 0;
            $idStartDialog = -1;
        }
        $text = $this->renderPartial("dialogs", array("items" => $items),true);
        echo json_encode(array("text" => $text, "idDialog" => $idStartDialog, "isConf" => $isConf));

    }

    public function actionGetDialogWithUser()
    {
        if ($_POST['isConf'])
        {
            $criteria = new CDbCriteria();
            $criteria->addCondition("idConference = ".$_POST['idUser']);
            $confs = Conference::model()->findAll($criteria);
            $users = array();
            foreach ($confs as $item)
            {
                $users[$item->idUser] = $item->user;
            }
            $criteria = new CDbCriteria();
            $criteria->addCondition("idRecepient = ".$_POST['idUser']." AND isConference = 1");
            $criteria->order = "dateSend DESC";
            $criteria->limit = 10;
            $messages = Message::model()->findAll($criteria);
            $text = $this->renderPartial('dialog', array('messages' => array_reverse($messages), 'user' => $users, 'isConference' => true),true);
        } else
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
            $text = $this->renderPartial('dialog', array('messages' => array_reverse($messages), 'user' => $user, 'isConference' => false),true);
        }

        echo json_encode(array("text" => $text));
    }

    public function actionSendMessage()
    {
        $criteria = new CDbCriteria();
        $idUser = $_POST["idUser"];
        $criteria->compare("idAutor",Yii::app()->user->getId());
        $criteria->compare("isConference",$_POST["isConference"]);
        $criteria->compare("idRecepient",$idUser);
        $criteria->order = "dateSend DESC";
        $criteria->limit = 1;
        $message = Message::model()->find($criteria);
        $timeDiffToCreateNewMessage = 60*60;
        // TODO:: вынести в настройки
        if (!$_POST["isConference"] && $message != null && strtotime(date("Y-m-d H:i:s")) - strtotime($message->dateSend) < $timeDiffToCreateNewMessage)
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
            $message->isConference = $_POST["isConference"];
            if ($_POST["ad"] > 0)
                $ad = 1;
            else
                $ad = 0;
            $message->isPublishedOnMain = $ad && Yii::app()->user->isAdmin();
            $message->save(false);
        }
    }

    public function actionAjaxGetUsers()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition("id <> ".Yii::app()->user->id);
        $users = User::model()->findAll($criteria);
        $arr = array();
        foreach ($users as $user)
        {
            if ($user->role == ROLE_TEACHER)
                $role = "Преподаватель";
            if ($user->role == ROLE_STUDENT)
            {
                $role = "Студент ";
                $groups = StudentGroup::model()->findAll("idStudent = :id", array(':id' => $user->id));
                $flag = true;
                foreach ($groups as $item)
                {
                    if (!$flag)
                        $role.=", ";
                    $group = Group::model()->findByPk($item->idGroup);
                    $role.= $group->Title;
                    $flag = false;
                }
            }
            if ($user->role == ROLE_ADMIN)
                $role = "Администратор";
            $item = array();
            $item['value'] = $user->id;
            $item['text'] = $user->fio;
            $item['selected'] = false;
            $item['description'] = $role;
            $item['imageSrc'] = $user->getAvatarPath(AVATAR_SIZE_MINI);
            $arr[] = $item;
        }
        $item = array();
        $item['value'] = -1;
        $item['text'] = "Никого не нашли :(";
        $item['selected'] = false;
        $item['description'] = "Попробуйте уточнить данные поиска";
        $item['imageSrc'] = "";
        $arr[] = $item;
        echo json_encode($arr);
    }

    public function actionAjaxGetGroups()
    {
        $groups = Group::model()->findAll();
        $arr = array();
        foreach ($groups as $group)
        {
            $item = array();
            $item['value'] = $group->id;
            $item['text'] = $group->Title;
            $item['selected'] = false;
            $arr[] = $item;
        }
        $item = array();
        $item['value'] = -1;
        $item['text'] = "Никого не нашли :(";
        $item['selected'] = false;
        $item['description'] = "Попробуйте уточнить данные поиска";
        $item['imageSrc'] = "";
        $arr[] = $item;
        echo json_encode($arr);
    }


    public function actionIndex($startDialog = -1)
    {
        $this->render('view', array("startDialog" => $startDialog));

    }

    public function addServiceMessage($idConference, $text)
    {
        $message = new Message();
        $message->idAutor = $idConference;
        $message->idRecepient = $idConference;
        $message->status = 0;
        $message->text = $text;
        $message->isConference = true;
        $message->isService = true;
        $message->save(false);
    }

    public function actionAjaxAddToConference($idConference, $idUser, $isConference)
    {
        $newConference = $idConference;
        if (!$isConference)
        {
            // Если конференции нет, то нужно создать новую
            $conference = new Conference();
            $conference->idConference = Conference::getNextIdConference();
            $conference->idUser = Yii::app()->user->getId();
            $conference->save();
            $newConference = $conference->idConference;
            if ($idConference != Yii::app()->user->getId())
            {
                $conference = new Conference();
                $conference->idConference = $newConference;
                $conference->idUser = $idConference;
                $conference->save();
            } else
            {
                $this->addServiceMessage($newConference, Yii::app()->user->getModel()->getShortFio()." создал новую конференцию");
            }
        }
        Conference::model()->deleteAll("idUser = :us AND idConference = :conf", array(":us" => $idUser, ":conf" => $idConference));
        $addConf = new Conference();
        $addConf->idConference = $newConference;
        $addConf->idUser = $idUser;
        if ($idUser <> Yii::app()->user->getId())
        {
            $addConf->save();
            $user = User::model()->findByPk($idUser);
            $this->addServiceMessage($newConference, Yii::app()->user->getModel()->getShortFio()." добавил в конференцию ".$user->getShortFio());
        }
        $result = array();
        $result["idConference"] = $newConference;
        echo json_encode($result);
    }

    public function actionAjaxAddGroupToConference($idConference, $idGroup, $isConference)
    {
        $group = Group::model()->findByPk($idGroup);
        foreach ($group->students as $student)
        {
            Conference::model()->deleteAll("idUser = :us AND idConference = :conf", array(":us" => $student->id, ":conf" => $idConference));
            $addConf = new Conference();
            $addConf->idConference = $idConference;
            $addConf->idUser = $student->id;
            $addConf->save();
        }
        $this->addServiceMessage($idConference, Yii::app()->user->getModel()->getShortFio()." добавил в конференцию ".$group->Title);
        $result = array();
        $result["idConference"] = $idConference;
        echo json_encode($result);
    }

    public function actionAjaxDeleteFromConference()
    {
        $idUser = $_POST["idUser"];
        $idConference = $_POST["idConference"];
        $user = User::model()->findByPk($idUser);
        Conference::model()->deleteAll("idConference = :conf AND idUser = :us", array(":conf" => $idConference, ":us" => $idUser));
        $this->addServiceMessage($idConference, Yii::app()->user->getModel()->getShortFio()." удалил из конференции ".$user->getShortFio());
    }

    public function actionAjaxGetConferenceUsers()
    {
        $idUser = $_POST["idUser"];
        if ($_POST["isConference"])
        {
            $confs = Conference::model()->findAll("idConference = :us", array("us" => $idUser));
            $users = array();
            foreach ($confs as $item)
            {
                $users[] = $item->user;
            }
            $this->renderPartial("conferenceUsers", array("users" => $users));
        } else
        {
            $users = array();
            $users[] = Yii::app()->user->getModel();
            if ( Yii::app()->user->getId() <> $idUser)
                $users[] = User::model()->findByPk($idUser);
            $this->renderPartial("conferenceUsers", array("users" => $users));

        }
    }

    public function actionDeleteMessage()
    {
        if (Yii::app()->user->isAdmin())
        {
            $idMessage = $_POST['idMessage'];
            Message::model()->deleteByPk($idMessage);
        }
    }

}
