<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 15.04.15
 * Time: 21:26
 * To change this template use File | Settings | File Templates.
 */?>

<?php foreach ($items as $item):?>
    <div class="dialog <?php if ($item["hasNonReadable"]) echo "unread" ?>" onclick="getDialogWithUser(<?php echo $item["user"]->id; ?>)" data-idUser = "<?php echo $item["user"]->id; ?>"">
        <div class="row">
            <div class="col-xs-3">
                <a href="#" class="avatar" style="background-image: url(<?php echo $item["user"]->getAvatarPath(); ?>)"></a>
                <span class="new">НОВОЕ</span>
            </div>
            <div class="col-xs-9">
                <a href="#"><?php echo $item["user"]->getShortFio(); ?></a>
                <p>
                    <span class="preview"><?php echo $item["message"]->text; ?></span>
                    <br>
                    <span class="date"><?php if ($item["message"] != null) echo DateHelper::getDifference($item["message"]->dateSend,date("Y-m-d H:i:s")); ?></span>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>