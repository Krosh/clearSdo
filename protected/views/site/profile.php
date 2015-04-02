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
                            <img style= "float: left; max-width: 30%; max-height: 300px" src = "/avatars/<?php echo $model->avatar; ?>">
                            <p style="float: right; min-width: 65%; max-width: 65%">
                                <?php echo $model->info; ?>
                            </p>
                        </div>

                </div>

            </div>
