<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 12.10.14
 * Time: 17:50
 * To change this template use File | Settings | File Templates.
 */
echo "<div>";
echo "<h1>".$group->Title."</h1>";
echo "<table><tr><td>ФИО студента</td><td>Оценка</td></tr>";
foreach ($group->students as $student)
{
    echo "<tr><td>".$student->fio."</td><td>";
    if ($needEdit)
        echo CHtml::textField("Mark[".$student->id."]",ControlMaterial::getMark($student->id,$idControlMaterial), array('onchange' => 'saveMark('.$student->id.','.$idControlMaterial.',$(this).val())'));
    else
        echo ControlMaterial::getMark($student->id,$idControlMaterial);
    echo "</td></tr>";
}

echo "</table></div>";
?>
