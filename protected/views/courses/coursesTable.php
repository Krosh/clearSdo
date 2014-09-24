<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */
/* @var $this CoursesController */
/* @var $courses Array*/
?>
<table class="all-courses">
<?php
foreach ($courses as $item)
{
echo '<tr>
    <td>
        <a href="#"><span>'.$item->title.'</span></a>
    </td>
    <td>
        <div class="right">
            <a href="/viewCourse?idCourse='.$item->id.'">Перейти на страницу курса</a>
        </div>
    </td>
</tr>';
}
?>
</table>
