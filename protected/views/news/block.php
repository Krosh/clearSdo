<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 18:02
 * To change this template use File | Settings | File Templates.
 */?>

<div class="sidebar-item">
    <div class="sidebar-title">
        Новости
    </div>
    <?php if (Yii::app()->params["connectedWithAltSTU"]):?>
    <div class="sidebar-content" id = "news-content">
    </div>
    <?php endif; ?>
</div>
