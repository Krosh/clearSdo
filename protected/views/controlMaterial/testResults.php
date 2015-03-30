<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 23.09.14
 * Time: 20:29
 * To change this template use File | Settings | File Templates.
 */
/* @var $questions Array*/
/* @var $mark Array*/
/* @var $answerContent Array*/
/* @var $model UserControlMaterial*/
/* @var $summWeight int */

$controlMaterial = ControlMaterial::model()->findByPk($model->idControlMaterial);
?>


<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title"><?php echo $controlMaterial->title; ?></div>

                        <br>
                        <dl class="test-info clearfix">
                            <dt>Выполнил:</dt>
                            <dd>1/1</dd>

                            <dt>Тест начат:</dt>
                            <dd><?php echo $model->dateStart?></dd>

                            <dt>Тест окончен:</dt>
                            <dd><?php echo $model->dateEnd ?></dd>

                            <dt>Время выполнения:</dt>
                            <dd>
                                <?php
                                    $time = strtotime($model->dateEnd)-strtotime($model->dateStart);
                                    $stringTime = "";
                                    $stringTime = ($time % 60)." сек.";
                                    if ($time > 60)
                                    {
                                        $time /= 60;
                                        $stringTime = ($time % 60)." мин ".$stringTime;
                                        if ($time>60)
                                        {
                                            $time /= 60;
                                            $stringTime = ($time % 60)." ч. ".$stringTime;
                                        }
                                    }
                                    echo $stringTime;
                                ?>
                            </dd>

                            <dt>Оценка:</dt>
                            <dd class="<?php if ($model->mark>=25) echo "green"; else echo "red" ;?>"><strong><?php echo $model->mark?></strong>  баллов</dd>
                        </dl>

                        <div class="clearfix"></div>
                    </div>

                    <?php $i = 0; ?>
                    <?php foreach($questions as $item): ?>
                        <div class="text-block">
                            <div class="block-header">
                                <?php echo $item->content; ?>
                            </div>
                            <div class="block-content">
                                <div class="col-group">
                                    <div class="col-6">
                                        <ul class="test-answers">
                                            <?php
                                            $answers = explode(", ",$answerContent[$i]);
                                            foreach ($answers as $curAnswer)
                                            {
                                                echo "<li>$curAnswer</li>";
                                            }
                                            ?>
                                        </ul>

                                        <dl class="test-answer-info clearfix">
                                            <dt>Время на ответ:</dt>
                                            <dd>84 сек.</dd>

                                            <dt>Суммарная оценка ответа:</dt>
                                            <dd class="<?php if ($mark[$i]>=25) echo "green"; else echo "red" ;?>"><?php echo $mark[$i]; ?>%</dd>

                                            <dt>Штраф:</dt>
                                            <dd>0%</dd>

                                            <dt>Итого:</dt>
                                            <dd>
                                                <?php
                                                   $bonus = $mark[$i] * $questions[$i]->weight / $summWeight;
                                                   echo "+".round($bonus,2);
                                                ?>
                                            </dd>
                                        </dl>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                    <? endforeach ?>
                </div>
            </div>