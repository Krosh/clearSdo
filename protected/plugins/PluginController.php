<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 21.12.14
 * Time: 3:01
 * To change this template use File | Settings | File Templates.
 */

class PluginController {
    public static $plugins = array();

    public static function init()
    {
        $plugins = array();
        $path = "application.plugins";
        $result = array();

        $i = 0;
        if ($handle = opendir(Yii::getPathOfAlias($path))) {
            while (false !== ($file = readdir($handle))) {
                if ($file == "." || $file == "..") continue;
                if (is_dir(Yii::getPathOfAlias($path).DIRECTORY_SEPARATOR.$file))
                {
                    $name = $file."Plugin";
                    PluginController::$plugins[] = new $name;
                    PluginController::$plugins[$i]->id = $i;
                    $i++;
                }
            }
            closedir($handle);
        }
    }
}


