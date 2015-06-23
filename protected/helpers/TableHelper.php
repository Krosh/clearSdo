<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 0:34
 * To change this template use File | Settings | File Templates.
 */

class TableHelper {
    public $title;
    public $headerRow;
    public $colRows;


    public function printTable()
    {
        Yii::app()->controller->renderPartial("/report/table", array('model' => $this));
    }
}