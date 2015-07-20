<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 22.09.14
 * Time: 19:11
 * To change this template use File | Settings | File Templates.
 */
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">

                    <div class="page-heading">
                        <div class="page-title">Настройки системы</div>
                    </div>
                    <?php if ($isSaved): ?>
                        <div class = "success">
                            Настройки сохранены
                        </div>
                    <?php endif; ?>

                    <div class="form">

                        <?php $form=$this->beginWidget('CActiveForm', array(
                            'htmlOptions' => array('enctype' => 'multipart/form-data'),
                            'id'=>'config-form',
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // There is a call to performAjaxValidation() commented in generated controller code.
                            // See class documentation of CActiveForm for details on this.
                            'enableAjaxValidation'=>false,
                        )); ?>


                        <table width="80%">
                            <tr>
                                <td width="35%">
                                    <?php echo $form->labelEx($config,'idActiveTerm'); ?>
                                </td>
                                <td width="65%" class="input-full-width">
                                    <?php
                                    $terms = Term::model()->findAll();
                                    $arr = array();
                                    foreach ($terms as $item)
                                    {
                                        $arr[$item->id] = $item->title;
                                    }
                                    ?>
                                    <?php echo $form->dropDownList($config,'idActiveTerm',$arr, array()); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%">
                                    <?php echo $form->labelEx($config,'activeTimezone'); ?>
                                </td>
                                <td width="65%" class="input-full-width">
                                    <?php
                                    $arr = array_merge(timezone_identifiers_list(DateTimeZone::ASIA),timezone_identifiers_list(DateTimeZone::EUROPE));
                                    $timezonesArr = array();
                                    foreach ($arr as $key => $value)
                                    {
                                        $timezonesArr[$value] = $value;
                                    }
                                    ?>
                                    <?php echo $form->dropDownList($config,'activeTimezone',$timezonesArr, array()); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="35%">
                                    <?php echo CHtml::label("Текущее время на сервере:",""); ?>
                                </td>
                                <td width="65%" class="input-full-width">
                                    <?php
                                        echo date("H:i:s P e");
                                    ?>
                                </td>
                            </tr>
                        </table>


                        <div class="row buttons">
                            <?php echo CHtml::submitButton($config->isNewRecord ? 'Создать' : 'Сохранить', array("class" => "btn blue")); ?>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div><!-- form -->

                </div>
            </div>
