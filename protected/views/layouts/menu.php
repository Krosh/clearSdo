<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 19.04.15
 * Time: 22:32
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="nav">
    <div class="small-nav">
        <img src="/img/icon-menu.png" alt="">
    </div>
    <div class="big-nav">
        <div class="link">
            <a href="/">Главная</a>
        </div>
  <!--      <div class="link">
            <a href="#">Общение</a>
        </div>-->
        <!--  <div class="link">
              <a href="#">Документы</a>
          </div>
        -->  <?php if (Yii::app()->user->isAdmin()):?>
            <div class="link">
                <a href="/config">Настройки</a>
            </div>
            <div class="link">
                <a href="/log">Логи</a>
            </div>
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
            <div class="link more">
                <a href="#">Медиатека</a>
                <div class="more-menu">
                    <div class="more-menu-links">
                        <a href="<?php echo $this->createUrl("/resources/learnMaterials"); ?>">Обучающие материалы</a>
                        <a href="<?php echo $this->createUrl("/resources/courses")?>"><span>Курсы</span></a>
                        <a href="<?php echo $this->createUrl("/resources/controlMaterials")?>"><span>Контрольные материалы</span></a>
                    </div>
                </div>
            </div>
        <? endif; ?>
    </div>
</div>
