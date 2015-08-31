<?php
/* @var $this UserController */
/* @var $model User */
?>


<body class="small-page">

<div class="login disable-loader">
    <div class="center">
        <div class="logo">
            <img src="/img/logo-big.png" width="66" height="66" alt="" data-retina>
            <span>Стимул</span>
        </div>
        <h2>Интерактивная Образовательная Среда</h2>
        <h3>Алтайский государственный технический университет им. И.И. Ползунова</h3>
    </div>
    
    <?php $form=$this->beginWidget('CActiveForm', array(
        'method' => 'POST',

        'id'=>'forgotForm'
    )); ?>
        <div class="title">Восстановление пароля</div>
        Ваш логин: <strong><?=$login?></strong>
        <br>
        Временный пароль: <strong><?=$newpassword?></strong>
        <br><br>
        <div class="center"><a href="/">Перейти к логину</a></div>
    <?php $this->endWidget(); ?>

    <div class="copyright">Copyright © 2008-<?=date("Y")?>, все права защищены <a href="#">СДО Стимул</a></div>
</div>