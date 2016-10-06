<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class ApiController extends CController
{
    public $layout = "/layouts/full";
    public $breadcrumbs;

    public function actionRegister($email)
    {
        $user = new User();
        $user->idGroup = 1;
        $user->role = ROLE_STUDENT;
        $user->email = $email;
        $user->fio = $email;
        $user->login = $email;
        $user->password = rand(111111,999999);
        if (!$user->save())
        {

        } else
        {

        }
    }


}