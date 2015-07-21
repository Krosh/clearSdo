<?php
/* @var $this AccessControlMaterialGroupController */
/* @var $model AccessControlMaterialGroup */
/* @var $form CActiveForm */
?>

<?php echo CHtml::beginForm('','post',array('class' => 'accessForm', 'id' => 'accessForm'.$model->id)); ?>
    <div class="form">

        <div class="row">
            <?php if ($model->type_relation == ACCESS_RELATION_GROUP): ?>
                <?php
                $arr = Course::getGroups($idCourse);
                $res = array();
                foreach ($arr as $item)
                {
                    $res[$item->id] = $item->Title;
                }
                echo CHtml::label("Группа","", array('style' => 'width: 50px;'));
                $this->widget('ext.combobox.EJuiComboBox', array(
                    'name' => 'GroupSelect',
                    'selectedId' => $model->idRecord,
                    'value' => $res[$model->idRecord],
                    'data' => $res,
                    'options' => array(
                        'allowText' => false,
                    ),
                    'htmlOptions' => array('size' => 15, 'class' => 'combobox' , 'onSelect' => 'ajaxUpdateAccess(this);'),
                ));?>
            <?php endif; ?>

            <?php if ($model->type_relation == ACCESS_RELATION_PERSONAL): ?>
                <?php
                $arr = Course::getUsers($idCourse);
                $res = array();
                foreach ($arr as $item)
                {
                    $res[$item->id] = $item->fio;
                }
                echo CHtml::label("ФИО","", array('style' => 'width: 50px;'));
                $this->widget('ext.combobox.EJuiComboBox', array(
                    'name' => 'GroupSelect',
                    'selectedId' => $model->idRecord,
                    'value' => $res[$model->idRecord],
                    'data' => $res,
                    'options' => array(
                        'allowText' => false,
                    ),
                    'htmlOptions' => array('size' => 15, 'class' => 'combobox' , 'onSelect' => 'ajaxUpdateAccess(this);'),
                ));?>
            <?php endif; ?>

            <?php echo CHtml::activeHiddenField($model,'id')?>
            <?php $categories = array(1 => 'Открыт', 2 => 'Закрыт', 3 => 'По времени', 4 => 'Последовательно'); ?>
            <?php echo CHtml::label('Доступ',null,array('style' => 'width: 50px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'accessType',$categories, array('class' => 'accessTypeList', 'onchange' => 'ajaxUpdateAccess(this); updateAccessDivs($(this.form))')); ?>
            <div class = "dateAccess" data-id ="<?php echo $model->id?>" style="display: inline">
                <?php echo CHtml::label('Дата начала',null); ?>
                <?php echo CHtml::textField('AccessControlMaterial[startDate]',DateHelper::getRussianDateFromDatabase($model->startDate,true),array('class' => 'dateTimePicker', 'onchange' => 'ajaxUpdateAccess(this);'));?>
                <div class = "endDateDiv" style="display: inline">
                    <?php echo CHtml::label('Дата окончания',null); ?>
                    <?php echo CHtml::textField('AccessControlMaterial[endDate]',DateHelper::getRussianDateFromDatabase($model->endDate,true),array('class' => 'dateTimePicker', 'onchange' => 'ajaxUpdateAccess(this);'));?>
                    <!--      TODO:: добавить кнопку для очистки даты закрытия
                    -->
                </div>
            </div>

            <div class = "beforeAccess" data-id ="<?php echo $model->id?>" style="display: inline">
                <?php echo CHtml::label('После прохождения какого теста дать доступ',null); ?>
                <?php

                $criteria = new CDbCriteria();
                $criteria->compare('idCourse',$idCourse);
                $criteria->order = "zindex";
                $coursesMaterials = CoursesControlMaterial::model()->findAll($criteria);

                $items = array();
                foreach ($coursesMaterials as $item)
                {
                    $cm = ControlMaterial::model()->findByPk($item->idControlMaterial);
                    $items[$cm->id] = $cm->title;
                }
                ?>
                <?php echo CHtml::activeDropDownList($model,"idBeforeMaterial", $items,array ('class' => 'datePicker', 'onchange' => 'ajaxUpdateAccess(this); ')); ?>

                <?php echo CHtml::label('Минимальная оценка',null); ?>
                <?php echo CHtml::activeTextField($model,"minMark", array('onchange' => 'ajaxUpdateAccess(this); ', 'style' => 'width: 40px')); ?>
            </div>
            <?php if ($model->type_relation != ACCESS_RELATION_COMMON):?>
                <a style="padding-left:10px" class="red" href="#" onclick="ajaxDeleteAccess(<?php echo $model->id.",".$idCourse.",".$idMaterial ?>); return false"><i class="fa fa-remove"></i></a>
            <?php endif; ?>

        </div>


    </div><!-- form -->
<?php echo Chtml::endForm(); ?>