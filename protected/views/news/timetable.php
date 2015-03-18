<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 18:02
 * To change this template use File | Settings | File Templates.
 */
header('Access-Control-Allow-Origin: *');
header('Content-type: text/html; charset=UTF-8');
$url = 'http://www.altstu.ru/main/schedule/';
$group = Group::model()->findByPk(3);
$text = $this->getContent($url,array("group" => $group->id_altstu));
preg_match_all("-<div class=\"schedule\">(.*)<div id=\"aside\">-s",$text,$matches);
$text = $matches[1][0];

$DAYS_NAMES = array("Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
$WEEK_NAMES = array("1 неделя","2 неделя");
$weekPattern = '~(<h3[^>]*>([0-9А-Яа-я ]*)</h3>|Экзамены)~u';
$dayPattern = '#<th colspan="4" [^>]*>([А-Яа-я ]*)</th>#u';
$lessonPattern = '#<tr>[.|\s]*<td>.*</td>\s*<td>.*</td>\s*<td.*>.*</td>\s*<td>.*</td>\s*</tr>#u';
$lessonInfoPattern = '#<td[^>]*>(.*)</td>#u';
$weeks = preg_split($weekPattern,$text,null,PREG_SPLIT_DELIM_CAPTURE);
Timetable::model()->deleteAll("idGroup = ".$group->id);
for ($numWeek = 2; $numWeek<count($weeks)-1; $numWeek+=3)
{
    $nameWeek = $weeks[$numWeek];
    $textWeek = $weeks[$numWeek+1];
    $days = preg_split($dayPattern,$textWeek,null,PREG_SPLIT_DELIM_CAPTURE);
    for ($numDays = 1; $numDays<count($days); $numDays+=2)
    {
        $nameDay = $days[$numDays];
        $textDay = $days[$numDays+1];
        $lessonInfos = preg_split($lessonInfoPattern,$textDay,null,PREG_SPLIT_DELIM_CAPTURE);
        for ($numLessonInfo = 0; $numLessonInfo<count($lessonInfos)-1; $numLessonInfo+=8)
        {
            $time = $lessonInfos[$numLessonInfo+1];
            $name = strip_tags($lessonInfos[$numLessonInfo+3]);
            $room = strip_tags($lessonInfos[$numLessonInfo+5]);
            $teacher = strip_tags($lessonInfos[$numLessonInfo+7]);
            echo $nameWeek." ".$nameDay." ".$time." ".$teacher." ".$room." ".$name."<br>";
            $timetable = new Timetable();
            $timetable->idGroup = $group->id;
            $timetable->day = array_search($nameDay,$DAYS_NAMES);
            $timetable->numWeek = array_search($nameWeek,$WEEK_NAMES);
            $timetable->time = $time;
            $timetable->name = $name;
            $timetable->room = $room;
            $timetable->teacher = $teacher;
            if (!$timetable->save())
                echo "error";
        }

    }


}
return;

$text = strip_tags($matches[1][0]);
$days = array("Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
$text = str_replace("\r\n","",$text);
$text = str_replace("\n","",$text);
$text = str_replace("&nbsp","",$text);
$mas = explode(" ",$text);
$rasp = array('first' => array(array(),array(),array(),array(),array(),array()),'second' => array(array(),array(),array(),array(),array(),array()));
$isStart = true;
$curDay = 0;
$isFirstWeek = true;
$i = 0;
while ($isFirstWeek)
{
    $item = $mas[$i];
    if (strlen($item) < 1)
    {
        $i++;
        continue;
    }
    if (array_search($item,$days) !== false)
    {
        $isFirstWeek = false;
    }
    else
    {
        $i += 1;
    }

}
while ($i<count($mas))
{
    $tempCurPos = array_search($mas[$i],$days);
    if ($tempCurPos<$curDay)
    {
        // Меняем неделю
    }
    $i+=1;
    $time = $mas[$i];
    $title = "";
    $i+=1;
    while (mb_strtoupper($mas[$i],"UTF-8") == $mas[$i])
    {
        $title.=$mas[$i]." ";
        $i++;
    }
    if (substr($mas[$i],0,1) == "(")
    {
        do
        {
            $title.=$mas[$i]." ";
            $i++;
        }
        while (substr($mas[$i],strlen($mas[$i])-1,1) != ")");
    }
    $i += 1;
    $room = "";
    if ($mas[$i]+1>0)
    {
        $room=$mas[$i]." ".$mas[$i+1];
        $i+=2;
        while (array_search($mas[$i],$days) === false && strpos($mas[$i],":")=== false)
        {
            $i++;
        }
    }
    echo $title." ".$room."<br>";
    $i++;

}
// Возможные проблемы - пары только на второй неделе
