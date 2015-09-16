<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 31.12.13
 * Time: 12:23
 * To change this template use File | Settings | File Templates.
 */

class WebUser extends CWebUser {
    private $_model = null;

    public function myCheckAccess($needRole)
    {
        $roles = array('0', '1', '2');
        $role = Yii::app()->user->getRole();
        return (array_search($needRole,$roles)<=array_search($role,$roles));
    }

    function isStudent()
    {
        return ($this->getRole() == 0);
    }

    function isTeacher()
    {
        return ($this->getRole() == 1);
    }

    function isAdmin()
    {
        return ($this->getRole() == 2);
    }

    function isAdminOnForum($idForum = -1)
    {
        if ($idForum <= 0)
            return $this->isAdmin();
        Yii::import('application.modules.yii-forum.models.*');
        $forum = Forum::model()->findByPk($idForum);
        return $forum->hasAdminAccess();
    }

    function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
    }

    function getLanguage()
    {
        if($user = $this->getModel()){
            return $user->defaultLanguage;
        }
    }

    function getFio() {
        if ($user = $this->getModel())
        {
            $mas = explode(" ",$user->fio);
            $text = $mas[0]." ".mb_substr($mas[1],0,1,"UTF-8").".".mb_substr($mas[2],0,1,"UTF-8").".";
            return $text;
        }
    }

    function getAvatarPath($needImageSize = AVATAR_SIZE_NORMAL) {
        if ($user = $this->getModel()) {
            return $user->getAvatarPath($needImageSize);
        }
    }

    function getLastVisit() {
        if ($user = $this->getModel())
        {
            return $user->lastVisit;
        }
    }

    public function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }

    public function sendNotification($text, $type)
    {
        $message = array("text" => $text,"type" => $type);
        Yii::app()->user->setFlash(rand(0,9999999),$message);
    }


}