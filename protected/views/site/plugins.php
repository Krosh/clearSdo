<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
/* @var PluginController $plugin */
?>


<div class="wrapper">
    <div class="container">
    <div class="col-group">
    <div class="col-9">

        <div class="content">
            <div class="page-heading">
                <div class="page-title"><?php echo $plugin->fullName; ?></div>
            </div>

            <?php
            $plugin->render($params,$this);
            ?>


        </div>

    </div>
