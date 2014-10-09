<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class ActiveTestFilter extends CFilter {
    public function preFilter($filterChain) {
        if (isset(Yii::app()->session['currentTestGo']))
        {
            $idGo = Yii::app()->session['currentTestGo'];
            if ($idGo == -1)
                return true;
            Yii::app()->controller->redirect(Yii::app()->controller->createUrl("/controlMaterial/question"));
        }
        return true;
    }
    public function postFilter($filterChain) {}
}