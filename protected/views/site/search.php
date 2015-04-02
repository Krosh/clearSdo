<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */
/* @var $model Course */
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title">Результаты поиска: <?php echo $query; ?></div>
                    </div>
                    <div>
                        <table class="all-courses">
                            <?php foreach ($users as $user):?>
                                <tr data-href = "/profile?idUser=<?php echo $user->id; ?>">
                                    <td width="77%">
                                        <div class="page-title"><?php echo $user->fio; ?></div>
                                    </td>
                                    <td>
                                        <div class="right">
                                            <img style="border-radius: 50%; max-height: 100px; max-width: 100px" src="<?php echo "/avatars/".DIRECTORY_SEPARATOR.$user->avatar; ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                    </div>
                </div>
            </div>
