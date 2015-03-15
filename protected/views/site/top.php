<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 20:43
 * To change this template use File | Settings | File Templates.
 */?>
<body>
<header>
    <div class="container">
        <div class="col-group">
            <div class="col-7 col-mb-3">
                <div class="logo">
                    <a href="/">
                        <img src="/img/logo-small.png" height="40" width="40" alt="" data-retina>
                        <span>Стимул</span>
                    </a>
                </div>
                <div class="nav">
                    <div class="small-nav">
                        <img src="/img/icon-menu.png" alt="">
                    </div>
                    <div class="big-nav">
                        <div class="link">
                            <a href="/">Главная</a>
                        </div>
                        <div class="link">
                            <a href="#">Общение</a>
                        </div>
                      <!--  <div class="link">
                            <a href="#">Документы</a>
                        </div>
                      -->  <?php if (Yii::app()->user->isAdmin()):?>
                            <div class="link more">
                                <a href="#">Справочники</a>
                                <div class="more-menu">
                                    <div class="more-menu-links">
                                        <a href="<?php echo $this->createUrl("group/admin")?>"><span>Группы</span></a>
                                        <a href="<?php echo $this->createUrl("user/admin")?>"><span>Пользователи</span></a>
                                        <a href="<?php echo $this->createUrl("term/admin")?>"><span>Периоды</span></a>
                                    </div>
                                </div>
                            </div>
                        <? endif; ?>
                        <?php if (isset(PluginController::$plugins)):?>
                            <div class="link more">
                                <a href="#">Плагины (<?php echo count(PluginController::$plugins)?>)</a>
                                <div class="more-menu">
                                    <div class="more-menu-links">
                                        <?php $i = 0; ?>
                                        <?php foreach (PluginController::$plugins as $item):?>
                                            <a href = "<?php echo $item->getUrl(); ?>"><span><?php echo $item->name?></span></a>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <? endif; ?>
                        <?php if (Yii::app()->user->isTeacher()):?>
                            <div class="link more">
                                <a href="#">Отчеты</a>
                                <div class="more-menu">
                                    <div class="more-menu-links">
                                        <a href="<?php echo $this->createUrl("report/marks")?>"><span>Успеваемость групп</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="link">
                                <a href="<?php echo $this->createUrl("/site/mediateka"); ?>">Медиатека</a>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-5 col-mb-9 right">
                <div class="search">
                    <form>
                        <input type="text" placeholder="Поиск">
                        <button type="submit"><i></i></button>
                    </form>
                </div>

                <div class="user">
                    <div class="language dropdown center">
                        Язык <i class="caret"></i>

                        <div class="dropdown-container">
                            <a href="#"><i class="lang-rus"></i></a>
                            <a href="#"><i class="lang-eng"></i></a>
                            <a href="#"><i class="lang-cn"></i></a>
                        </div>
                    </div>
                    <a href="#" class="mails">
                        <i class="mail"></i>
                        <span>2</span>
                    </a>
                    <div class="profile dropdown">
                        <?php
                        echo Yii::app()->user->getFio();
                        ?>
                        <i class="caret"></i>
                        <div class="dropdown-container">
                            <a href="<?php echo $this->createUrl("/site/userConfig");?>">Настройки</a>
                            <a href="/logout">Выход</a>
                        </div>
                    </div>
                    <div class="avatar">
                        <!-- <img src="/img/avatar-default.png" alt=""> -->
                        <?
                        $avatar = Yii::app()->user->getAvatar();
                        if(!empty($avatar)) {
                        ?> 
                            <div class="the-avatar-box" style="background-image: url('/avatars/<?=$avatar?>')"></div>
                        <? } else { ?>
                            <div class="the-avatar-box" style="background-image: url('/img/avatar-default.png')"></div>
                        <? } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<div class="container">
    <?php if(isset($this->breadcrumbs)):?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'homeLink'=>CHtml::link('Главная','/' ),
        )); ?>
    <?php endif?>
</div>
