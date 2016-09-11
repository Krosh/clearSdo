<?php

class WebinarController extends CController
{
    public $layout='//layouts/full';

    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            //        array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionConnectToConference($idMaterial)
    {
        $material = LearnMaterial::model()->findByPk($idMaterial);
        $mat = Webinar::model()->findByPk($material->content);
        $bbb=Yii::app()->bigbluebutton;
        $bbb->attendeePW=Yii::app()->params['attendeePW'];
        $bbb->moderatorPW=Yii::app()->params['moderatorPW'];

        if (Yii::app()->user->role == ROLE_TEACHER)
            $role = BigBlueButton::ROLE_MODERATOR;
        else
            $role = BigBlueButton::ROLE_VIEWER;
        $joinUrl=$bbb->getJoinMeetingUrl($mat->idWebinar, Yii::app()->user->getFio(), null, $role);
        $this->redirect($joinUrl);
    }

    public function actionStartConference($idMaterial)
    {
        $material = LearnMaterial::model()->findByPk($idMaterial);
        $webinar = Webinar::model()->findByPk($material->content);
        $bbb=Yii::app()->bigbluebutton;
        $bbb->attendeePW=Yii::app()->params['attendeePW'];
        $bbb->moderatorPW=Yii::app()->params['moderatorPW'];

        $meeting=$bbb->createMeeting($material->getViewedTitle());
        $webinar->idWebinar = $meeting['meetingID'];
        $webinar->status = STATUS_ACTIVE;
        $webinar->save();
    }

    public function actionGetInfo($idMaterial)
    {
        $mat = LearnMaterial::model()->findByPk($idMaterial);
        $bbb=Yii::app()->bigbluebutton;
        $bbb->attendeePW=Yii::app()->params['attendeePW'];
        $bbb->moderatorPW=Yii::app()->params['moderatorPW'];
        try
        {
            $meeting=$bbb->getMeetingForUser(
                $mat->content,
                "AnotherUser",
                //user id will be taken from Yii::app()->user->id
                null,
                BigBlueButton::ROLE_MODERATOR);
            CVarDumper::dump($meeting, 10, 1);
        }
        catch (BigBlueButtonException $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function actionChangePublicStatus($id)
    {
        $material = LearnMaterial::model()->findByPk($id);
        $webinar = Webinar::model()->findByPk($material->content * 1);
        $webinar->isPublic = 1 - $webinar->isPublic;
        $webinar->save();
    }

    public function actionChangePassword($id,$password)
    {
        $material = LearnMaterial::model()->findByPk($id);
        $webinar = Webinar::model()->findByPk($material->content);
        $webinar->password = $password;
        $webinar->save();
    }

    static public function actionGetRecords($idMaterial)
    {
        $material = LearnMaterial::model()->findByPk($idMaterial);
        $webinar = Webinar::model()->findByPk($material->content);

        $bbb=Yii::app()->bigbluebutton;
        $bbb->attendeePW=Yii::app()->params['attendeePW'];
        $bbb->moderatorPW=Yii::app()->params['moderatorPW'];
        $result = $bbb->getRecordings($webinar->idWebinar);
        CVarDumper::dump($result,10,true);
    }

    public function actionGetAllMeetings()
    {
        $bbb=Yii::app()->bigbluebutton;
//set default passwords (you may also set them from configuration)

        $meetings=$bbb->getMeetingsForUser(
        //username which will be shown in BigBlueButton client
            "TestUser2",
            //user id
            457,
            //request running meetings only (default is any meetings)
            BigBlueButton::MEETING_STATE_RUNNING,
            //generate meeting interaction urls with viewer privileges
            BigBlueButton::ROLE_VIEWER);
//array of meetings with urls for user to join or end meeting
        CVarDumper::dump($meetings, 10, 1);
        echo "<br/>";


        $meetings=$bbb->getFullMeetings(BigBlueButton::MEETING_STATE_COMPLETED);
        CVarDumper::dump($meetings, 10, 1);
        echo "<br/>";
    }

    public function actionConnect($id)
    {
        $material = LearnMaterial::model()->findByPk($id);
        if ($material == null)
            throw new Exception("Такого вебинара не зарегистрировано");

        $error = false;
        $form = new LoginWebinarForm();
        if(isset($_POST['LoginWebinarForm']))
        {
            $form->attributes=$_POST['LoginWebinarForm'];
            $mat = Webinar::model()->findByPk($material->content);
            if ($mat == null || !$mat->isPublic)
                throw new Exception("Такого вебинара не зарегистрировано");
            if ($mat->password == $form->password)
            {
                $bbb=Yii::app()->bigbluebutton;
                $bbb->attendeePW=Yii::app()->params['attendeePW'];
                $bbb->moderatorPW=Yii::app()->params['moderatorPW'];

                $role = BigBlueButton::ROLE_VIEWER;
                $joinUrl=$bbb->getJoinMeetingUrl($mat->idWebinar, $form->username, null, $role);
                $this->redirect($joinUrl);
            } else
                $error = true;
        }
        $this->layout = "/layouts/main";
        $this->render("connect",array("model" => $form, "title" => $material->title, "error" => $error));
    }



}
