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
                        <img src="/img/logo-small.png" alt="">
                        <span>Стимул</span>
                    </a>
                </div>
                <nav>
                    <div class="small-nav">
                        <img src="/img/icon-menu.png" alt="">
                    </div>
                    <div class="big-nav">
                        <div class="link active">
                            <a href="/">Главная</a>
                        </div>
                        <div class="link">
                            <a href="#">Общение</a>
                        </div>
                        <div class="link">
                            <a href="#">Документы</a>
                        </div>
                        <div class="link">
                            <a href="#">Обучение</a>
                        </div>
                        <div class="link">
                            <a href="#">Форум</a>
                        </div>
                        <div class="link more">
                            <a href="#">Справочники</a>
                            <div class="more-menu">
                                <div class="more-menu-links">
                                    <a href="#">Группы</a>
                                    <a href="#">Пользователи</a>
                                    <a href="#">Периоды</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
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
                            <a href="#">Настройки</a>
                            <a href="/logout">Выход</a>
                        </div>
                    </div>
                    <div class="avatar">
                        <img src="/img/avatar-default.png" alt="">
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

