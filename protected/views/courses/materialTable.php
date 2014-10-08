<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 30.09.14
 * Time: 13:14
 * To change this template use File | Settings | File Templates.
 */?>
<table class="table green" id = "learnMaterialTable">
<thead>
<tr>
    <th colspan="2" width="70%" class="left">Файл</th>
    <th></th>
</tr>
</thead>
<tbody>
<?php
$criteria = new CDbCriteria();
$criteria->compare('idCourse',$idCourse);
$criteria->order = "zindex";
$coursesMaterials = CoursesMaterial::model()->findAll($criteria);

?>
<?php foreach ($coursesMaterials as $currentCourseMaterial):?>
    <?php $item = LearnMaterial::model()->findByPk($currentCourseMaterial->idMaterial); ?>
    <? if($item->category != MATERIAL_TITLE) { ?>
        <tr id = "<?php echo $currentCourseMaterial->id; ?>"<!--data-href="--><?php /*echo $this->createUrl("/learnMaterial/getMaterial", array("matId" => $item->id)) */?>">
    <? } else { ?>
        <tr id = "<?php echo $currentCourseMaterial->id; ?>">
    <? } ?>

    <?php if ($item->category == MATERIAL_TITLE):?>
        <td class="title" colspan="3">
            <!-- <i class="fileicon-file"></i> -->
            <?= $item->title;?>
            <div style="float:right;">
                <a class="btn white small" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>)"><i class="fa fa-remove"></i></a>
            </div>
        </td>
    <? else: ?>
        <td>
            <?
            $path = "";
            if ($item->category==MATERIAL_FILE) {
                $path = pathinfo($item->path, PATHINFO_EXTENSION);
            }
            
            $f = "";
            
            switch ($path) {
                case "docx":
                    $f = "file";
                    break;
                case "txt":
                    $f = "file";
                    break;
                case "rtf":
                    $f = "file";
                    break;
                case "doc":
                    $f = "file";
                    break;
                case "pdf":
                    $f = "pdf";
                    break;
                case "xls":
                    $f = "excel";
                    break;
                case "xlsx":
                    $f = "excel";
                    break;
                case "csv":
                    $f = "excel";
                    break;
                case "ppt":
                    $f = "presentation";
                    break;
                case "pptx":
                    $f = "presentation";
                    break;
                case "zip":
                    $f = "archive";
                    break;
                case "rar":
                    $f = "archive";
                    break;
                case "7z":
                    $f = "archive";
                    break;
                case "tar":
                    $f = "archive";
                    break;
                case "gz":
                    $f = "archive";
                    break;
                case "jpg":
                    $f = "image";
                    break;
                case "jpeg":
                    $f = "image";
                    break;
                case "bmp":
                    $f = "image";
                    break;
                case "png":
                    $f = "image";
                    break;
                case "gif":
                    $f = "image";
                    break;
                case "avi":
                    $f = "movie";
                    break;
                case "mpg":
                    $f = "movie";
                    break;
                case "mp4":
                    $f = "movie";
                    break;
                case "mov":
                    $f = "movie";
                    break;
                case "torrent":
                    $f = "torrent";
                    break;
                default:
                    $f = "no";
            }
            ?>
            <img class="file-icon" src="/img/fileicons/<?=$f?>.png" alt="">
            <?
            
            echo $item->title;
            if(strlen($path) > 0) {
                echo "." . $path;
            }
            ?>
        </td>
        <td class="right">
            <?php
            $sizeText = "";
            if ($item->category == MATERIAL_FILE)
            {
                $size = filesize($item->getPathToMaterial());
                $sizePrefixxes = array(" Б"," Кб", " Мб", " Гб");
                $i = 0;
                do
                {
                    $sizeText = $size.$sizePrefixxes[$i];
                    $i++;
                    $size = floor($size/1024);
                } while ($size>0);
            }
            echo $sizeText;
            ?>

        </td>
        <td class="right">
            <a class="btn white small" href="#" onclick="deleteLearnMaterial(<?php echo $idCourse?>,<?php echo $item->id; ?>)"><i class="fa fa-remove"></i></a>
        </td>
    <? endif; ?>
    </tr>
<?php endforeach; ?>
</tbody>
</table>
