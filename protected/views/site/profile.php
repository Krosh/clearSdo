<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">

                    <div class="page-heading">
                        <div class="page-title">Профиль пользователя <?php echo $model->fio; ?></div>
                    </div>
                        <div>
                            <img style= "float: left; max-width: 30%;" src = "/avatars/<?php echo $model->avatar; ?>">
                            <p style="float: right; min-width: 65%; max-width: 65%">
                                <i class="fa fa-phone"></i><phone><?php echo $model->phone; ?></phone><br>
                                <i class="fa fa-mail"></i><a href = "mailto:<?php echo $model->email; ?>"><?php echo $model->email; ?></a><br>
                                <a href = '<?php echo $this->createUrl("/message/index", array("startDialog" => $model->id))?> '>Написать сообщение</a>
                                <br>
                                <?php echo str_replace("\n","<br>",$model->info); ?>
                            </p>
                        </div>

                </div>
            </div>
