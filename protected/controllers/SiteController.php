<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{
    public $layout = "/layouts/full";
    public $noNeedJquery = false;
    public $breadcrumbs;


    public function filters()
    {
        return array(
            array('application.filters.AccessFilter'),
            array('application.filters.ActiveTestFilter'),
            array('application.filters.TimezoneFilter'),
        );
    }

    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect('/');
    }

    public function actionIndex()
    {
        // Проверка авторизации
        $auth = false;
        $model = new User();
        if(Yii::app()->user->isGuest)
        {
            if (isset($_POST['User']))
            {
                $model->attributes = $_POST['User'];
                $identity=new UserIdentity($model->login,$model->password);
                if($identity->authenticate())
                {
                    Yii::app()->user->setFlash('message','Авторизация прошла успешно');
                    Yii::app()->user->login($identity);
                    $auth = true;
                    $this->redirect('/site/index');
                }
                else
                {
                    $auth = false;
                }
            }
        }
        else
        {
            $auth = true;

        }
        if ($auth)
            $this->render("mainPage");
        else
        {
            $this->layout = "/layouts/main";
            $this->render("loginForm",array('model' => $model));
        }
    }

    public function actionViewCourse($idCourse)
    {
        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            // Бросить ошибку
        }
        Yii::app()->session['currentCourse'] = $idCourse;
        $this->render('viewCourse', array('model' => $course));
    }

    public function actionEditCourse($idCourse)
    {
        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }
        Yii::app()->session['currentCourse'] = $idCourse;
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => $idCourse)))
        );
        if(isset($_POST['Course']))
        {
            $course->attributes=$_POST['Course'];
            if($course->save())
                $this->refresh();
        }

        $this->noNeedJquery = true;
        $this->render('editCourse', array('model' => $course));
    }

    public function actionJournal($idCourse,$idGroup, $print = false)
    {
        $course = Course::model()->findByPk($idCourse);
        if ($course == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }
        $group = Group::model()->findByPk($idGroup);
        if ($group == null)
        {
            throw new CHttpException(404,'Не ломайте стимул!!');
        }

        if(Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial("/journal/table", array("idCourse" => $idCourse, "group" => $group));
        }
        else
        {
            $this->breadcrumbs=array(
                $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => $idCourse))),
                "Журнал ".$group->Title => array($this->createUrl("/site/journal",array("idCourse" => $idCourse, "idGroup" => $idGroup)))
            );
            if ($print)
            {
                $this->layout = "";
                $this->render("/journal/table", array("idCourse" => $course->id, "group" => $group));
            } else
                $this->render("/journal/view", array("course" => $course, "group" => $group));
        }
    }

    public function actionConfig()
    {
        $isSaved = false;
        $config = Config::model()->findByPk(1);
        if(isset($_POST['Config']))
        {
            $config->attributes=$_POST['Config'];
            if($config->save())
                $isSaved = true;
        }
        $this->render('config',array("config" => $config, "isSaved" => $isSaved));
    }

    public function actionNoAccess()
    {
        $this->render("noAccess");
    }


    public function actionPlugin($id)
    {
        $this->noNeedJquery = true;
        $params = $this->getActionParams();
        unset($params["id"]);
        if (Yii::app()->request->isAjaxRequest)
        {
            PluginController::$plugins[$id]->render(array_merge($params,$_POST),$this);
        }
        else
            $this->render("plugins", array("plugin" => PluginController::$plugins[$id], "params" => $params));
    }


    public function actionUserConfig()
    {
        $model=User::model()->findByPk(Yii::app()->user->getId());

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $changeAccepted = false;
        if(isset($_POST['User']))
        {
            $flag = true;
            $model->attributes=$_POST['User'];
            if ($_POST['haveNewPassword'])
            {
                if ($model->password == md5($_POST["oldPassword"]))
                {
                    if ($_POST["newPassword"] == $_POST["confirmNewPassword"])
                    {
                        $model->password = md5($_POST["newPassword"]);
                        Yii::app()->user->setFlash("codeMessage","success");
                        Yii::app()->user->setFlash("message","Пароль изменен");
                        $flag = false;
                    } else
                    {
                        Yii::app()->user->setFlash("codeMessage","error");
                        Yii::app()->user->setFlash("message","Введенные пароли не совпадают");
                        $flag = false;
                    }

                } else
                {
                    Yii::app()->user->setFlash("codeMessage","error");
                    Yii::app()->user->setFlash("message","Старый пароль введен неверно");
                    $flag = false;
                }
            }
            $model->newAvatar = $_POST['User']['newAvatar'];
            $model->save();

        }

        $this->render('userConfig/update',array(
            'model'=>$model,
        ));
    }

    public function actionWebinar()
    {
        $bbb=Yii::app()->bigbluebutton;
//set default passwords (you may also set them from configuration)
        $bbb->attendeePW=123;
        $bbb->moderatorPW=12345;

//create simple meeting with default parameters and generate join url for TestUser
        $meeting=$bbb->createMeeting("test");
//see what response we've got from server
        CVarDumper::dump($meeting, 10, 1);
        echo "<br/>";

//join url is constructed with viewer privileges by default
        $joinUrl=$bbb->getJoinMeetingUrl($meeting['meetingID'], "TestUser");
//redirect user to conference immediately...
//$this->redirect($joinUrl);
//...or send him a link to join manually
        echo CHtml::link("Join my cool conference!", $joinUrl)."<br/>";

//check if created meeting is running
//(actually it isn't, because we just created it, but
// there are no participants)
        $isRunning=$bbb->meetingIsRunning($meeting['meetingID']);
        echo "meeting state: ".(($isRunning)?"running":"not running")."<br/>";

//show all meetings
        $meetings=$bbb->getMeetings();
        CVarDumper::dump($meetings, 10, 1)."<br/>";
        echo "<br/>";

//imagine you have authenticated user somewhere...
        // Yii::app()->user->id=456;

//get created meeting for another user
//with moderator privileges to join and end conference
        $meeting=$bbb->getMeetingForUser(
            $meetings[0]['meetingID'],
            "AnotherUser",
            //user id will be taken from Yii::app()->user->id
            null,
            BigBlueButton::ROLE_MODERATOR,
            //moderator password
            $meetings[0]['moderatorPW']);
        CVarDumper::dump($meeting, 10, 1);
        echo "<br/>";

//now before we could end meeting we need at least one member there
//to get it started, otherwise it won't be counted as 'running'.
//so use join url as shown above and open conference in another tab.

//if it's time to end meeting, you should do like this:
//$bbb->endMeeting($meeting['meetingID'], $meeting['moderatorPW']);
//or this, because we have meeting data with moderator privileges:
        echo CHtml::link("End that boring conference", $meeting['endUrl'])."<br/>";
//but do not simply show that link to real user, use getApiResponse()
//or endMeeting() methods instead

//show all running conferences for specified user
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

//the list of ended meetings with full information about them
        $meetings=$bbb->getFullMeetings(BigBlueButton::MEETING_STATE_COMPLETED);
        CVarDumper::dump($meetings, 10, 1);
        echo "<br/>";
//...or the list of all meetings with less information,
//but much faster on large number of meetings
        $meetings=$bbb->getMeetings(BigBlueButton::MEETING_STATE_ANY);
        CVarDumper::dump($meetings, 10, 1);
        echo "<br/>";

//build a link to create another meeting with a bunch of options
        $newMeetingUrl=$bbb->getCreateMeetingUrl(
            "My awesome show",
            array(
                //set meeting id if you don't want it to be created automatically.
                //may be you'll manage meetings with your own database
                //and you can supply it's id here
                "meetingID"=>356,
                //free access to any viewer, overwriting password from configuration
                "attendeePW"=>"",
                //some custom moderator password
                "moderatorPW"=>"myhardestpasswordever",
                //another logout url...
                "logoutUrl"=>Yii::app()->createAbsoluteUrl(
                    "conferences/logout"),
                //welcome chat message
                "welcome"=>"Hello world!",
                //+any parameters as described in API docs..
            )
        );
//you may store url somewhere and call later
//$meeting=$bbb->getApiResponse($newMeetingUrl);
//or call $bbb->createMeeting() with same parameters as above
//to create it immediately

//check server state
        $isOnline=$bbb->serverIsAvailable();
//hope it's true
        echo $bbb->url." server availability: ".(($isOnline)?"ok":"fail");
        echo "<br/>";

//and don't forget to catch exceptions
        $bbb->url="http://google.com";
        try
        {
            //will be false
            $isOnline=$bbb->serverIsAvailable();
            echo $bbb->url." server availability: ".(($isOnline)?"ok":"fail");
            echo "<br/>";
            //will throw an exception
            $meetings=$bbb->getMeetings();
        }
        catch (BigBlueButtonException $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function actionProfile($idUser)
    {
        $user = User::model()->findByPk($idUser);
        if ($user == null || $user->role != ROLE_TEACHER)
            throw new CHttpException(404,"");
        $this->render('/site/profile', array('model' => $user));

    }

    public function actionSearch($query)
    {
        // Поиск по людям
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('fio',$query);
        $users = User::model()->findAll($criteria);
        $this->render('/site/search', array('query' => $query, 'users' => $users));
    }

    public function actionLog()
    {
        $this->noNeedJquery = true;
        $model=new Log('search');
        $model->unsetAttributes();
        if(isset($_GET['Log']))
            $model->attributes=$_GET['Log'];

        $this->render('log',array(
            'model'=>$model,
        ));
    }




}