<?php

class LearnMaterialController extends CController
{
    public $layout = "/layouts/main";

    public function filters()
    {
        return array(
            array('application.filters.TimezoneFilter')
        );
    }

    public function actionGetMaterial($matId)
    {
        // TODO:: Проверка прав доступа
        $mat = LearnMaterial::model()->findByPk($matId);
        $filename = $mat->getPathToMaterial();
        $newname = $mat->path;

        $newname = str_replace(",","",$newname);
        $newname = str_replace("#","",$newname);
        $newname = str_replace(" ","_",$newname);

        if(ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        $file_extension = strtolower(substr(strrchr($filename,"."),1));
        if( $filename == "" )
        {
            echo "Error: name of file not found.";
            exit;
        } elseif ( ! file_exists( $filename ) )
        {
            echo "ERROR: file notfound.";
            exit;
        };
        switch( $file_extension )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "mp3": $ctype="audio/mp3"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg": $ctype="image/jpg"; break;
            case "jpg": $ctype="image/jpg"; break;
            default: $ctype="application/force-download";
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=".$newname.";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filename));
        readfile("$filename");
    }

}
