<?php
/* @var $this UserController */
/* @var $model LoginWebinarForm */
/* @var $form CActiveForm */
?>

<body class="small-page">

<div class="login <?=$model->username != "" ? "disable-loader" : ""?>" style="<?=$model->username != "" ? "" : "display: none"?>">
    <div class="center">
        <div class="logo">
            <img src="/img/logo-big.png" width="66" height="66" alt="" data-retina>
            <span>Стимул</span>
        </div>
        <h2>Интерактивная Образовательная Среда</h2>
   <!--     <h3>Алтайский государственный технический университет им. И.И. Ползунова</h3>
 -->   </div>
    
    <div class="login-tab">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'method' => 'POST',

            'id'=>'loginWebinarForm',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
        )); ?>
            <div class="title">Присоединяйтесь к вебинару</div>
            <div class="title"><?=$title; ?></div>

            <?php echo $form->textField($model,'username',array('placeholder' => "Ваше имя")); ?>
            <?php echo $form->passwordField($model,'password',array('placeholder' => "Пароль для подключения")); ?>

            <button type="submit">Присоединиться</button>

            <?php if ($error): ?>
            <div id = "wrongPassword" class="center red">Неправильный логин или пароль</div>
            <?php endif; ?>
        <?php $this->endWidget(); ?>
    </div>
    
    <div class="copyright">Copyright © 2008-<?=date("Y")?>, все права защищены <a href="http://www.sdo-stimul.ru">СДО Стимул</a></div>
</div>

