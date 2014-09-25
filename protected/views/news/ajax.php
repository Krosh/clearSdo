<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 25.09.14
 * Time: 18:02
 * To change this template use File | Settings | File Templates.
 */
header('Access-Control-Allow-Origin: *');
header('Content-type: text/html; charset=UTF-8');
$MAX_NEWS_COUNT = 3;
$url = 'http://www.altstu.ru/feeds/news/';
$xml = xml_parser_create();
xml_parser_set_option($xml, XML_OPTION_SKIP_WHITE, 1);
xml_parse_into_struct($xml, $this->getContent($url,array()), $element, $index);
xml_parser_free($xml);
$count = min(count($index["TITLE"])-1,$MAX_NEWS_COUNT);
?>
<?php for ($i=0; $i < $count; $i++):?>
    <?php
    $text = $element[$index["DESCRIPTION"][$i+1]]["value"];
    $pattern = '~<a href.*/a>~i';
    $text = str_replace("<br>"," ",$text);
    $text = strip_tags($text);
    $text = str_replace("Читать дальше","",$text);
    $time = DateTime::createFromFormat(DateTime::RSS, $element[$index["PUBDATE"][$i+1]]["value"]);
    ?>
    <div class="sidebar-small-item">
        <a href="<?php $element[$index["LINK"][$i+1]]["value"]; ?>"><?php echo $text?></a>
        <div class="description"><i><?php echo $time->format("d.m.Y")?></i></div>
    </div>
<?php endfor; ?>
