<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */
class TimezoneFilter extends CFilter {
    public function preFilter($filterChain) {
        date_default_timezone_set(Yii::app()->params['timezone']);
        return true;
    }
    public function postFilter($filterChain) {}
}