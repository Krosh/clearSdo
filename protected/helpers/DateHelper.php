<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.09.14
 * Time: 0:34
 * To change this template use File | Settings | File Templates.
 */

class DateHelper {
    public static function getRussianDateFromDatabase($date,$isDateTime = false)
    {
        if ($isDateTime)
        {
            $startDate = $date;
            $mas = explode(" ",$startDate);
            $date = explode("-",$mas[0]);
            return $date[2].".".$date[1].".".$date[0]." ".$mas[1];
        } else
        {
            $mas = explode("-",$date);
            return $mas[2].".".$mas[1].".".$mas[0];
        }
    }

    public static function getDatabaseDateFromRussian($date,$isDateTime = false)
    {
        if ($isDateTime)
        {
            $startDate = $date;
            $mas = explode(" ",$startDate);
            $date = explode(".",$mas[0]);
            return $date[2]."-".$date[1]."-".$date[0]." ".$mas[1];
        } else
        {
            $mas = explode(".",$date);
            return $mas[2]."-".$mas[1]."-".$mas[0];
        }
    }

    public static function getTimestampFromDateTime($dateTime)
    {
        $date = explode(" ",$dateTime);
        $dayArray = explode("-",$date[0]);
        $timeArray = explode(":",$date[1]);
        return mktime($timeArray[0],$timeArray[1],$timeArray[2],$dayArray[1],$dayArray[2],$dayArray[0]);
    }

    public static function getDifference($date1, $date2)
    {
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $difference = $time2-$time1;
        if ($difference<60)
            return "Только что";
        if ($difference < 120)
            return "Минуту назад";
        if ($difference < 3600)
            return (floor($difference/60))." минут(ы) назад";
        if ($difference < 7200)
            return "Час назад";
        if ($difference< 60*60*24)
            return (floor($difference/3600))." час(ов) назад";
        if ($difference< 60*60*24*30)
            return (floor($difference/3600/24))." дней назад";
        if ($difference< 60*60*24*30*12)
            return (floor($difference/3600/24))." месяцев назад";
        if ($difference< 60*60*24*30*12*2)
            return "год назад";
//        if ($difference< 60*60*24*30*12)
            return (floor($difference/3600/24/30/12))." лет назад";

    }

}