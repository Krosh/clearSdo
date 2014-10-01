<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form CActiveForm */

?>

    <div class = "answer">
    <?php
        // Делается для унификации обработки вопросов на соответствие и обычных
        // В вопросах на соответствие соответствующие варианты хранятся через символ '~'
        $texts = explode('~', $model->content);
    ?>

	<div class="row">
		<?php echo CHtml::label("Содержимое ответа", "");?>
        <?php echo CHtml::textArea('content'.$model->id, $model->content,array('rows'=>6, 'cols'=>50)); ?>
	</div>

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
		<?php echo CHtml::checkBox('right'.$model->id, $model->right); ?>
	</div>

    <button  onclick="changeAnswer(<?php echo $model->id?>,$('#content<?php echo $model->id; ?>').val(),$('#right<?php echo $model->id; ?>').val() ); return false">Сохранить</button>
    </div>
    <!-- form -->