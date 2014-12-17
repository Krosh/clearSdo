<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form CActiveForm */

?>

<div class = "answer wide">
    <?php
    // Делается для унификации обработки вопросов на соответствие и обычных
    // В вопросах на соответствие соответствующие варианты хранятся через символ '~'
    $texts = explode('~', $model->content);
    if ($needSecondAnswer)
        $changeAnswerFunc = 'changeAnswer('.$model->id.',$("#content'.$model->id.'").val()+"~"+$("#secondContent'.$model->id.'").val(),$("#right'.$model->id.'").prop("checked"));';
    else
        $changeAnswerFunc = 'changeAnswer('.$model->id.',$("#content'.$model->id.'").val()+"~",$("#right'.$model->id.'").prop("checked"));';
    ?>

    <div class="row">
        <?php echo CHtml::label("Вариант ответа", "");?>
        <?php echo CHtml::textField('content'.$model->id, $texts[0],array('onchange' => $changeAnswerFunc)); ?>
    </div>
    <?php if ($needSecondAnswer): ?>
        <div class="row">
            <?php echo CHtml::label("Соответствующий вариант", "");?>
            <?php echo CHtml::textField('secondContent'.$model->id, $texts[1],array('onchange' => $changeAnswerFunc)); ?>
        </div>
    <?php endif; ?>


    <!--    --><?php
    /*    // TODO:: разобраться почему не работает с константами
        if ($questionType == 5)
        {
            echo '<div class="row">';
                echo CHtml::label("Соответствующий вариант", "");
                echo CHtml::textArea('match'.$num, $texts[1],array('rows'=>6, 'cols'=>50));
            echo '</div>';
        }
        */?>

    <div class="row">
        <?php echo CHtml::label("Правильность", "");?>
        <?php echo CHtml::checkBox('right'.$model->id, $model->right, array('onclick' => $changeAnswerFunc)); ?>
    </div>

</div>
<!-- form -->