<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.08.16
 * Time: 23:13
 * To change this template use File | Settings | File Templates.
 */

class LoginWebinarForm extends CFormModel
{
    public $username;
    public $password;

    public function rules()
    {
        return array(
            array('username, password', 'safe'),
        );
    }
}