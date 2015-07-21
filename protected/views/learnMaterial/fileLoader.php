<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 19.11.14
 * Time: 0:32
 * To change this template use File | Settings | File Templates.
 */?>
<?php
Yii::import( "xupload.models.XUploadForm" );
$model = new XUploadForm();
$this->widget('xupload.XUpload', array(
    'url' => Yii::app()->createUrl("/learnMaterial/upload"),
    'model' => $model,
    'attribute' => 'file',
    'multiple' => true,
    'formView' => 'application.views.learnMaterial.form',
    'uploadTemplate' => 'application.views.learnMaterial.form.upload',
    'downloadTemplate' => 'application.views.learnMaterial.form.upload',
));
?>