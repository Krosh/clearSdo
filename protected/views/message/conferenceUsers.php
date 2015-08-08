<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 08.08.15
 * Time: 22:25
 * To change this template use File | Settings | File Templates.
 */?>
<?php $i = 1; ?>
<?php foreach ($users as $user): ?>
    <table width="100%">
        <tr>
            <td width="90%" class="left"><strong><?php echo $i++; ?>.</strong> <?php echo $user->fio ?></td>
            <?php if (count($users) > 2): ?>
                <td width="10%" class="right">
                    <a href="#" onclick="deleteFromConference(<?php echo $user->id; ?>); return false"><i class="fa fa-remove"></i></a>
                </td>
            <?php endif; ?>
        </tr>
    </table>
<?php endforeach; ?>
