<?php

class UserController extends ForumBaseController
{
    public $layout='//layouts/forum';
    
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array('accessControl');
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            // ALL users
            array('allow',
                'actions' => array('view'),
                'users' => array('*'),
            ),
            // authenticated users
            array('allow',
                'actions' => array('update'),
                'users' => array('@'),
            ),
/*

            // administrators
            array('allow',
                'actions' => array('create', 'update', 'delete'),
                'users' => array('@'), // Must be authenticated
                'expression' => 'Yii::app()->user->isAdmin', // And must be admin
            ),
*/

            // deny all users
            array('deny', 'users'=>array('*')),
        );
    }

    /**
     * Shows the given user's profille
     */
    public function actionView($id)
    {
        $user = Forumuser::model()->findByPk($id);
        if(null == $user)
            throw new CHttpException(404, 'Страница не найдена.');

        $this->render('view',array(
            'user'=>$user,
        ));
    }

    /**
     * Edit a user's information (signature)
     */
    public function actionUpdate($id)
    {
        // A user can onbly edit themselves, unless they're admin of course
        if(Yii::app()->user->isGuest || (
                !Yii::app()->user->isAdminOnForum() &&
                ($id!=Yii::app()->user->forumuser_id)
        ))
            throw new CHttpException(403, 'У вас нет доступа.');

        $user = Forumuser::model()->findByPk($id);
        if(null == $user)
            throw new CHttpException(404, 'Страница не найдена.');

        if(isset($_POST['Forumuser']))
        {
            $user->attributes=$_POST['Forumuser'];
            if($user->validate())
            {
                $user->save(false);
                $this->redirect($user->url);
            }
        }

        $this->render('update', array(
            'model'=>$user,
        ));
    }
}
