<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 15.04.15
 * Time: 21:26
 * To change this template use File | Settings | File Templates.
 */?>

<?php foreach ($items as $item):?>
    <div class="dialog <?php if ($item["hasNonReadable"]) echo "unread" ?>" data-idUser = "<?php echo $item["idUser"]; ?>" data-isConf = "<?php echo $item['isConf']; ?>">
        <div class="row">
            <div class="col-xs-3">
                <a href="#" class="avatar" style="background-image: url(<?php echo (!$item['isConf']) ? $item["user"]->getAvatarPath(AVATAR_SIZE_MEDIUM) : "" ?>)"></a>
                <span class="new">НОВОЕ</span>
            </div>
            <div class="col-xs-9">
                <?php
                    if ($item['isConf'])
                    {
                        $fioText = "";
                        foreach ($item["user"] as $user)
                        {
                            if ($fioText != "")
                                $fioText.= ", ";
                            $fioText.= $user->getShortFio();
                        }
                    } else
                    {
                        $fioText = $item["user"]->getShortFio();
                    }
                ?>
                <p style="text-overflow: ellipsis; overflow:hidden; height: 35px"><a href="#"><?php echo $fioText; ?></a></p>
                <p >
                    <span class="preview" style="text-overflow: ellipsis; overflow:hidden; height: 35px; display: inline-block"><?php echo $item["message"]->text; ?></span>
                    <br>
                    <span class="date"><?php if ($item["message"] != null) echo DateHelper::getDifference($item["message"]->dateSend,date("Y-m-d H:i:s")); ?></span>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>