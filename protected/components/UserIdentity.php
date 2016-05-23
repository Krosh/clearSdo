<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 31.12.13
 * Time: 12:13
 * To change this template use File | Settings | File Templates.
 */

class UserIdentity extends CUserIdentity {
    // Будем хранить id.
    protected $_id;

    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate(){
        // Производим стандартную аутентификацию, описанную в руководстве.
        $user = User::model()->find('LOWER(login)=?', array(strtolower($this->username)));
        if(($user===null) || (md5($this->password)!==$user->password)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            // В качестве идентификатора будем использовать id, а не username,
            // как это определено по умолчанию. Обязательно нужно переопределить
            // метод getId(см. ниже).
            $this->_id = $user->id;

            // Далее логин нам не понадобится, зато имя может пригодится
            // в самом приложении. Используется как Yii::app()->user->name.
            // realName есть в нашей модели. У вас это может быть name, firstName
            // или что-либо ещё.
            $this->username = $user->fio;
            $this->errorCode = self::ERROR_NONE;

            $user->noNeedToLog = true;
            $user->lastVisit = $user->curVisit;
            $user->curVisit = date("Y-m-d H:i:s");
            $user->save();
        }
        return !$this->errorCode;
    }

    public $errorMessage = 'Ошибка авторизации';

    public function getId(){
        return $this->_id;
    }
}