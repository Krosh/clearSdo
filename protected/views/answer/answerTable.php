<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.09.14
 * Time: 13:14
 * To change this template use File | Settings | File Templates.
 */
?>
<table class="table green" id = "answerTable">
    <thead>
    <tr>
        <th colspan="2" width="70%" class="left">Варианты ответа</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $criteria = new CDbCriteria();
    $criteria->compare('question',$idQuestion);
    $criteria->order = "zindex";
    $answers = Answer::model()->findAll($criteria);

    ?>
    <?php foreach ($answers as $item):?>
        <tr id = "<?php echo $item->id; ?>">

            <td >
                <?php
                    $this->renderPartial("/answer/_form", array("model" => $item));
                ?>
            </td>
            <td class="right">

            </td>
            <td class="right">
                <a class="btn red" href="#" onclick="deleteAnswer(<?php echo $item->id; ?>)"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
