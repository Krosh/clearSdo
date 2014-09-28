<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 0:34
 * To change this template use File | Settings | File Templates.
 */

class DateHelper {
    public static function getRussianDateFromDatabase($date)
    {
        $mas = explode("-",$date);
        return $mas[2].".".$mas[1].".".$mas[0];
    }

}