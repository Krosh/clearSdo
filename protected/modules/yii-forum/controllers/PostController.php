<?php

class PostController extends ForumBaseController
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
/*
            // ALL users
            array('allow',
                'actions' => array(),
                'users' => array('*'),
            ),
*/
            // authenticated users
            array('allow',
                'actions' => array('update'),
                'users' => array('@'),
            ),

            // administrators
            array('allow',
                'actions' => array('delete'),
                'users' => array('@'), // Must be authenticated
                'expression' => 'Yii::app()->user->isAdminOnForum()', // And must be admin
            ),

            // deny all users
            array('deny', 'users'=>array('*')),
        );
    }

    /**
     * deletePost action
     * Deletes post.
     */
    public function actionDelete($id)
    {
        if(!Yii::app()->request->isPostRequest || !Yii::app()->request->isAjaxRequest)
            throw new CHttpException(400, 'Неправильный запрос.');

        // First, we make sure it even exists
        $post = Post::model()->findByPk($id);
        if(null == $post)
            throw new CHttpException(404, 'Страница не найдена.');

        $post->delete();
    }

    /**
     * UPDATE action. Only accessible by author and admin
     */
    public function actionUpdate($id)
    {
        $post = Post::model()->findByPk($id);
        if(null == $post)
            throw new CHttpException(404, 'Ответ не найден.');
        if(!Yii::app()->user->isAdmin && YII::app()->user->id != $post->author_id)
            throw new CHttpException(403, 'У вас нет доступа к редактированию.');

        if(isset($_POST['Post']))
        {
            $post->attributes=$_POST['Post'];
            if($post->validate())
            {
                $post->save(false);
                $this->redirect($post->thread->url);
            }
        }
        $this->render('editpost', array(
            'model'=>$post,
        ));
    }

}