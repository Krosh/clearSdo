<?php

class LearnMaterialController extends CController
{
    public $layout='//layouts/full';
    public $breadcrumbs;

    public function filters()
    {
        return array(
            array('application.filters.ActiveTestFilter'),
            array('application.filters.AccessFilter'),
            array('application.filters.TimezoneFilter')
        );
    }

    public function actions()
    {
        return array(
            'upload'=>array(
                'class'=>'xupload.actions.XUploadAction',
                'path' =>Yii::app() -> getBasePath() . "/../uploads",
                'publicPath' => Yii::app() -> getBaseUrl() . "/uploads",
            ),
        );
    }

    public function actionCreate()
    {
        $mat = new LearnMaterial();
        $mat->idAutor = Yii::app()->user->getId();
        $mat->title = "Новый материал";
        $mat->category = MATERIAL_INBROWSER;
        if ($mat->save())
        {
            $this->addCourseMaterial($_GET['idCourse'],$mat->id);
            $this->redirect($this->createUrl("/learnMaterial/edit",array("idMaterial" => $mat->id)));
        } else
        {
            throw new CHttpException(404,'Ошибка в запросе');
        }
    }

    public function actionEdit($idMaterial)
    {
        $mat = LearnMaterial::model()->findByPk($idMaterial);
        if ($mat == null)
            throw new CHttpException(404,'Ошибка в запросе');
        if ($mat->idAutor != Yii::app()->user->getId())
            $this->redirect("/noAccess");
        if(isset($_POST['LearnMaterial']))
        {
            $mat->attributes=$_POST['LearnMaterial'];
            if($mat->save())
                $this->redirect($this->createUrl("/site/editCourse", array("idCourse" => Yii::app()->session['currentCourse'])));
        }

        $course = Course::model()->findByPk(Yii::app()->session['currentCourse']);
        $this->breadcrumbs=array(
            $course->title => array($this->createUrl("/site/editCourse",array("idCourse" => Yii::app()->session['currentCourse']))),
            $mat->title => array($this->createUrl("/learnMaterial/edit",array("idMaterial" => $idMaterial))),
        );
        $this->render("edit", array("model" => $mat));
    }

    public function actionDeleteMaterial()
    {
        $idMaterial = $_POST["idMaterial"];
        $idCourse = $_POST["idCourse"];
        $criteria = new CDbCriteria();
        $criteria->compare("idMaterial",$idMaterial);
        $criteria->compare("idCourse",$idCourse);
        $model = CoursesMaterial::model()->findAll($criteria);
        $z = $model[0]->zindex;
        CoursesMaterial::model()->deleteAll($criteria);
        $criteria = new CDbCriteria();
        $criteria->compare("idCourse",$idCourse);
        $criteria->addCondition("zindex > ".$z);
        $materials = CoursesMaterial::model()->findAll($criteria);
        foreach ($materials as $item)
        {
            $item->zindex = $item->zindex-1;
            $item->save();
        }
    }

    public function addCourseMaterial($idCourse,$idMaterial)
    {
        $courseMat = new CoursesMaterial();
        $courseMat->idCourse = $idCourse;
        $courseMat->idMaterial = $idMaterial;
        $courseMat->zindex = CoursesMaterial::model()->count("idCourse = ".$idCourse)+1;
        $courseMat->dateAdd = date("Y-m-d H:i:s");
        $courseMat->save();
    }

    public function actionChangeTitle()
    {
        $material = LearnMaterial::model()->findByPk($_POST['idMaterial']);
        if ($material->getExtension() != "")
            $material->title = $_POST['title'].".".$material->getExtension();
        else
            $material->title = $_POST['title'];
        $material->save();
    }

    public function actionAddMaterial()
    {
        $mat = new LearnMaterial();
        $mat->idAutor = Yii::app()->user->getId();
        $mat->title = $_POST["LearnMaterial"]["title"];
        $mat->category = $_POST["LearnMaterial"]["category"];
        $mat->category == MATERIAL_LINK ? $mat->path = $_POST["LinkPath"]:$mat->path = $_FILES['filePath']['name'];
        if ($mat->save())
        {
            $this->addCourseMaterial($_POST['idCourse'],$mat->id);
            echo "success";
        } else
        {
            echo "error";
        }
    }

    public function actionAddExistMaterial()
    {
        $this->addCourseMaterial($_POST['idCourse'],$_POST['idMaterial']);
    }

    public function actionOrderMaterial()
    {
        $idMat = $_POST["idMat"];
        $idParentMat = $_POST["idParentMat"];
        $mat = CoursesMaterial::model()->findByPk($idMat);
        if ($idParentMat != 0)
        {
            $parentMat = CoursesMaterial::model()->findByPk($idParentMat);
            $needZIndex = $parentMat->zindex;
        }
        else
        {
            $parentMat = new CoursesMaterial();
            $needZIndex = 0;
        }
        if ($needZIndex<$mat->zindex)
        {
            $criteria = new CDbCriteria();
            $criteria->compare("idCourse",$mat->idCourse);
            $criteria->addCondition("zindex > ".$needZIndex);
            $criteria->addCondition("zindex < ".$mat->zindex);
            $materials = CoursesMaterial::model()->findAll($criteria);
            foreach ($materials as $item)
            {
                $item->zindex = $item->zindex+1;
                $item->save();
            }
            $mat->zindex = $needZIndex+1;
            $mat->save();
        } else
        {
            $criteria = new CDbCriteria();
            $criteria->compare("idCourse",$mat->idCourse);
            $criteria->addCondition("zindex > ".$mat->zindex);
            $criteria->addCondition("zindex <= ".$needZIndex);
            $materials = CoursesMaterial::model()->findAll($criteria);
            foreach ($materials as $item)
            {
                $item->zindex = $item->zindex-1;
                $item->save();
            }
            $mat->zindex = $needZIndex;
            $mat->save();
        }
    }

    public function actionFullOrderMaterial()
    {
        $order = $_POST["newOrder"];
        $order = explode(",",$order);
        $i = 1;
        foreach ($order as $item)
        {
            if ($item<=0) return;
            $mat = CoursesMaterial::model()->findByPk($item);
            $mat->zindex = $i++;
            $mat->save();
        }
    }

    public function actionGetMaterials()
    {
        $idCourse = $_POST["idCourse"];
        $this->renderPartial("/courses/materialTable", array("idCourse" => $idCourse));
    }

    public function actionGetMaterial($matId)
    {
        $mat = LearnMaterial::model()->findByPk($matId);
        if ($mat->category == MATERIAL_INBROWSER)
        {
            $this->render("view", array("model" => $mat));
            Yii::app()->end();
        }
        if ($mat->category == MATERIAL_LINK)
        {
            $this->redirect($mat->path);
        }
        $filename = $mat->getPathToMaterial();
        $newname = $mat->title;
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

    public function actionFullDeleteMaterial($id)
    {
        $mat = LearnMaterial::model()->findByPk($id);
        // Проверка, является ли пользователь автором материала
        if ($mat->idAutor != Yii::app()->user->getId())
            return false;
        $criteria = new CDbCriteria();
        $criteria->compare("idMaterial", $id);
        CoursesMaterial::model()->deleteAll($criteria);
        $mat->delete();
    }

    public function actionDeleteAllNonUsedMaterials()
    {
        $criteria = new CDbCriteria();
        $criteria->compare("idAutor", Yii::app()->user->getId());
        $criteria->addInCondition("category",array(MATERIAL_FILE,MATERIAL_TORRENT));
        $data = LearnMaterial::model()->findAll($criteria);
        foreach ($data as $item)
        {
            if ($item->getCourses() == "")
                $item->delete();
        }

    }


}
