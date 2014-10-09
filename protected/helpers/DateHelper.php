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

    public static function getTimestampFromDateTime($dateTime)
    {
        $date = explode(" ",$dateTime);
        $dayArray = explode("-",$date[0]);
        $timeArray = explode(":",$date[1]);
        return mktime($timeArray[0],$timeArray[1],$timeArray[2],$dayArray[1],$dayArray[2],$dayArray[0]);
    }

}