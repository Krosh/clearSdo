<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<body class="small-page">

<div class="login <?=$model->login != "" ? "disable-loader" : ""?>" style="<?=$model->login != "" ? "" : "display: none"?>">
    <div class="center">
        <div class="logo">
            <img src="/img/logo-big.png" width="66" height="66" alt="" data-retina>
            <span>Стимул</span>
        </div>
        <h2>Интерактивная Образовательная Среда</h2>
        <h3>Алтайский государственный технический университет им. И.И. Ползунова</h3>
    </div>
    
    <div class="login-tab">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'method' => 'POST',

            'id'=>'loginForm',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
        )); ?>
            <div class="title">Вы не авторизованы!</div>
        
            <?php echo $form->textField($model,'login',array('placeholder' => "Логин")); ?>
            <?php echo $form->passwordField($model,'password',array('placeholder' => "Пароль")); ?>

            <input type="checkbox" id="remember-checkbox" name="remember" value="1" />
            <label for="remember-checkbox">Оставаться в системе</label>

            <button type="submit">Войти</button>

            <?php if ($model->login != ""): ?>
            <div id = "wrongPassword" class="center red">Неправильный логин или пароль</div>
            <?php endif; ?>
            <div class="center"><a href="#" data-open-forgot-form>Забыли пароль?</a></div>
        <?php $this->endWidget(); ?>
    </div>
    
    <div class="forgot-tab">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'method' => 'POST',

            'id'=>'forgotForm'
        )); ?>
            <div class="title">Восстановление пароля</div>
            <?php echo $form->textField($model,'email',array('placeholder' => "Ваш E-Mail")); ?>
            <button type="submit">отправить</button>
            <div class="center"><a href="#" data-open-login-form>Вспомнили пароль?</a></div>
        <?php $this->endWidget(); ?>
    </div>

    <div class="copyright">Copyright © 2008-<?=date("Y")?>, все права защищены <a href="#">СДО Стимул</a></div>
</div>

