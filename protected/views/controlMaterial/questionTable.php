<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.09.14
 * Time: 13:14
 * To change this template use File | Settings | File Templates.
 */?>
<table class="table green" id = "questionTable">
    <thead>
    <tr>
        <th colspan="3" width="70%" class="left">Вопрос</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $criteria = new CDbCriteria();
    $criteria->compare('idControlMaterial',$idControlMaterial);
    $criteria->order = "zindex";
    $questionsControlMaterials = QuestionsControlMaterial::model()->findAll($criteria);

    ?>
    <?php foreach ($questionsControlMaterials as $currentQCM):?>
        <?php $item = Question::model()->findByPk($currentQCM->idQuestion); ?>
        <tr id = "<?php echo $currentQCM->id; ?>" data-href = "/question/edit?id=<?php echo $item->id; ?>&idMaterial=<?php echo $idControlMaterial?>">
            <td>
                <img src = "<?php echo "/img/q".$item->type.".png"; ?>">
            </td>
            <td style="text-overflow: ellipsis">
                <?php
                    echo strip_tags($item->content);
                ?>
            </td>
            <td class="right">

            </td>
            <td class="right">
                <a class="btn white small" href="#" onclick="deleteQuestion(<?php echo $idControlMaterial?>,<?php echo $item->id; ?>)"><i class="fa fa-remove"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
