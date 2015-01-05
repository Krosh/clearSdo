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
                            <?php
                            if ($question->type == QUESTION_MATCH)
                            {
                                //TODO:: РїРѕРґСѓРјР°С‚СЊ, РєР°Рє РІС‹РЅРµСЃС‚Рё СЌС‚Сѓ Р»РѕРіРёРєСѓ РёР· РјРѕРґРµР»Рё
                                echo '<div class="radio-green">';
                                $leftAnswers = array();
                                $rightAnswers = array();
                                $shuffledId = array();
                                foreach ($answers as $item)
                                {
                                    $t = explode("~",$item->content);
                                    array_push($leftAnswers,$t[0]);
                                    array_push($rightAnswers,$t[1]);
                                    array_push($shuffledId, $item->id);
                                }
                                echo "<div id = 'leftAnswers'>";
                                foreach ($leftAnswers as $item)
                                {
                                    if ($item == "") continue;
                                    echo "<div class = 'leftAnswer'>".$item."<div class='the-arrow'></div></div>";
                                }
                                echo "</div>";

                                Yii::app()->session['rightShuffledId'] = $shuffledId;
                                for ($i = 0; $i<1000; $i++)
                                {
                                    $r1 = rand(0,count($rightAnswers)-1);
                                    $r2 = rand(0,count($rightAnswers)-1);
                                    $t = $rightAnswers[$r1];
                                    $rightAnswers[$r1] = $rightAnswers[$r2];
                                    $rightAnswers[$r2] = $t;
                                    $t = $shuffledId[$r1];
                                    $shuffledId[$r1] = $shuffledId[$r2];
                                    $shuffledId[$r2] = $t;
                                }

                                Yii::app()->session['shuffledId'] = $shuffledId;
                                echo "<div id = 'rightAnswers'>";
                                $i = 0;
                                foreach ($rightAnswers as $item)
                                {
                                    if ($item == "") continue;
                                    echo "<div class = 'rightAnswer' id = '$i'>".$item."</div>";
                                    $i++;
                                }
                                echo "</div>";

                                echo "<input id = 'answer' name = 'answer' type = 'hidden' >";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="test-submit">
                    <button type="submit" class="btn blue" onclick="checkSubmit(<?php echo $question->type; ?>)">Ответить</button>
                    <a href="/controlMaterial/skipQuestion" class="btn gray">Пропустить</a>
                </div>
            </form>

        </div>

    </div>
<?php
$this->renderPartial("/site/bottom");
?>