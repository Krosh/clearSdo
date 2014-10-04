<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 23.09.14
 * Time: 18:52
 * To change this template use File | Settings | File Templates.
 */?>
<div class="col-3">
    <div class="sidebar">

        <div class="sidebar-item">
            <div class="sidebar-title">
                Объявления
            </div>
            <div class="sidebar-content notice">
                <p>Поздравляем всех студентов с началом <strong>Нового Учебного года 2014-15</strong>!</p>
                <p>Тем, кто впервые работает в СДО Стимул рекомендуем ознакомиться с <a href="#">Руководством студента</a>.</p>
                <p>Желаем удачной учебы!</p>
            </div>
        </div>

        <?php $this->renderPartial("/news/block") ?>


        <div class="sidebar-item">
            <div class="sidebar-title">
                Расписание
            </div>
            <div class="sidebar-content">
                <div class="sidebar-small-item">
                    <span>ФИЗИЧЕСКАЯ КУЛЬТУРА</span>
                    <div class="description">09:55-11:25</div>
                </div>
                <div class="sidebar-small-item">
                    <span>АНГЛИЙСКИЙ ЯЗЫК (пр., подгруппа А)</span>
                    <div class="description">11:35-13:05, 537(7) ГК</div>
                </div>
                <div class="sidebar-small-item">
                    <span>ФИЗИЧЕСКАЯ КУЛЬТУРА</span>
                    <div class="description">09:55-11:25</div>
                </div>
                <div class="sidebar-small-item">
                    <span>АНГЛИЙСКИЙ ЯЗЫК (пр., подгруппа А)</span>
                    <div class="description">11:35-13:05, 537(7) ГК</div>
                </div>
            </div>
        </div>


    </div>
</div>
</div>
</div>
</div>

<footer class="fixed">
    <div class="container">
        <div class="col-6">
            Copyright © 2008-<?=date("Y")?>, все права защищены <a href="#">СДО Стимул</a>
        </div>
        <div class="col-6 right">
            СДО Стимул 2.0 &nbsp;&nbsp;&nbsp; <a href="#">Разработка системы</a>
        </div>
    </div>
</footer>
