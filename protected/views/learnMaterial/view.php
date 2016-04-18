<?php
/* @var $this LearnMaterialController */
/* @var $model LearnMaterial */
/* @var $form CActiveForm */
?>

<div class="wrapper">
    <div class="container">
        <div class="col-group">
            <div class="col-9">

                <div class="content">
                    <div class="page-heading">
                        <div class="page-title"><?php echo $model->title; ?></div>
                    </div>
                    <div class="getmaterial">
                        <?php
                            echo $model->content;
                        ?>
                    </div>
                </div>
            </div>
