<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 11.10.14
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */
/* @var $model TableHelper */

?>
    <h1><?php echo $model->title; ?></h1><br>
    <table width="100%" class="table table-left table-with-border">
        <thead>
            <tr>
                <?php foreach ($model->headerRow as $item):?>
                    <th>
                        <? echo $item; ?>
                    </th>
                <?php endforeach?>
            </tr>
        </thead>
        <?php foreach ($model->colRows as $row):?>
            <tr>
                <?php foreach ($row as $item):?>
                      <td <?php if (is_array($item)) echo $item["style"]; ?>>
                        <?php if (is_array($item))
                            echo $item["text"];
                        else
                            echo $item; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach;?>
    </table>
