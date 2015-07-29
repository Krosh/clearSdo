<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 28.07.15
 * Time: 14:26
 * To change this template use File | Settings | File Templates.
 */?>
<div class="col-5 col-mb-9 right">
    <div class="search">
        <form action="/search" method="GET">
            <input type="text" name = "query" placeholder="Поиск" value="<?php echo $_GET['query']; ?>">
            <button type="submit"><i></i></button>
        </form>
    </div>

    <div class="user">
        <div class="language dropdown center">
            Язык <i class="caret"></i>

            <div class="dropdown-container">
                <?php
                $languages = array("lang-rus" => "ru", "lang-eng" => "en", "lang-cn" => "cn");
                foreach ($languages as $key => $value)
                {
                    echo "<a href='#' onclick='ajaxChangeLanguage(\"$value\"); return false;'><i class='$key'></i></a>";
                }
                ?>
            </div>
        </div>
        <a href="/message/index" class="mails">
            <i class="mail"></i>
            <?php
            $sql = "SELECT COUNT(id) FROM `tbl_messages` WHERE STATUS = 0 AND idRecepient = ".Yii::app()->user->id;
            $command = Yii::app()->db->createCommand($sql);
            $res = $command->queryScalar();
            if ($res>0)
            {
                echo "<span>$res</span>";
            }
            ?>
        </a>
        <div class="profile dropdown">
            <?php
            echo Yii::app()->user->getFio();
            ?>
            <i class="caret"></i>
            <div class="dropdown-container">
                <a href="<?php echo $this->createUrl("/site/userConfig");?>">Настройки</a>
                <a href="/logout">Выход</a>
            </div>
        </div>
        <div class="avatar">
            <div class="the-avatar-box" style="background-image: url('<?php echo Yii::app()->user->getAvatar(AVATAR_SIZE_MINI); ?>')"></div>
        </div>
    </div>
</div>
