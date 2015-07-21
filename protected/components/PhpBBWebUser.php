<?php
class PhpBBWebUser extends WebUser{
    /** @var UserIdentity */
    private $_identity;
    public function login($identity, $duration=0) {
        $this->_identity = $identity;
        return parent::login($identity, $duration);
    }
    protected function afterLogin($fromCookie) {
        if ($this->_identity !== null) {
            if (Yii::app()->phpBB->login($this->_identity->username, $this->_identity->password) != 'SUCCESS') {
                Yii::log("Ошибка авторизации на форуме({$this->_identity->username})", CLogger::LEVEL_ERROR);
            }
        }
        parent::afterLogin($fromCookie);
    }
    protected function afterLogout() {
        Yii::app()->phpBB->logout();
        parent::afterLogout();
    }
}