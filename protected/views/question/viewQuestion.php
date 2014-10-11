<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 23.09.14
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */?>
<?php
$this->renderPartial('/site/top');
?>


<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading col-group">
                <div class="col-6">
                    <div class="page-title"><?php echo $test->title; ?></div>

                </div>
                <div class="col-6 right">
                    <i class="timer"></i>
                    до конца теста осталось: <span id = "timerSpan" class="red"></span>
                    <script>
                        window.endTime = "<?php echo date("Y-m-d H:i:s",$endTime )?>";
                    </script>
                </div>
            </div>
            <form METHOD="POST" name = "question" action = "<?php echo $this->createUrl('/controlMaterial/nextQuestion')?>">
                <div class="text-block">
                    <div class="block-header">
                        <div class="col-group">
                            <div class="col-10"><?php echo $question->content;?></div>
                            <div class="col-2 right"><div class="test-stat"><?php echo (Yii::app()->session['currentQuestion']+1)?>/<?php echo Yii::app()->session['totalQuestions']?></div></div>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="col-group">
                            <div class="col-6">
                                <?php if ($question->type == QUESTION_CHECKBOX):?>
                                    <div class="checkbox-green">
                                        <?php $i = 0; ?>
                                        <?php foreach ($answers as $item)
                                        {
                                            $content = str_replace("~","",$item->content);
                                            $id = $item->id;
                                            echo "<label><input type='checkbox' name='answer$i' value = '$id' /> $content</input></label>";
                                            $i++;
                                        }?>
                                    </div>
                                    <br>
                                <? endif ?>
                                <?php if ($question->type == QUESTION_RADIO):?>
                                    <div class="radio-green">
                                        <?php foreach ($answers as $item)
                                        {
                                            $content = str_replace("~","",$item->content);
                                            $id = $item->id;
                                            echo "<label><input type='radio' name='answer' value = '$id'/>$content</input></label>";
                                        }?>
                                    </div>
                                <? endif; ?>
                                <?php if ($question->type == QUESTION_NUMERIC || $question->type == QUESTION_TEXT):?>
                                    <div class="radio-green">
                                        <input name = 'answer' type = 'text' > </input>
                                    </div>
                                <? endif ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="test-submit">
                    <button type="submit" class="btn blue">Ответить</button>
                    <a href="/controlMaterial/skipQuestion" class="btn gray">Пропустить</a>
                </div>
            </form>

        </div>

    </div>
<?php
$this->renderPartial("/site/bottom");
?>