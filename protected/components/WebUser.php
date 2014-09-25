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
        return ($user->role == 0);
    }

    function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
    }

    function getFio() {
        if ($user = $this->getModel())
        {
            $mas = explode(" ",$user->fio);
            $text = $mas[0]." ".mb_substr($mas[1],0,1,"UTF-8").". ".mb_substr($mas[2],0,1,"UTF-8").".";
            return $text;
        }
    }

    public function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }


}