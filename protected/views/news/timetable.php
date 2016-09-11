<?php
if (!Yii::app()->params["connectedWithAltSTU"])
    return;
?>

<? /*
<div class="sidebar-item">
    <div class="sidebar-title">
        Расписание
    </div>
    <div class="sidebar-content">
        <?php
        if (Yii::app()->user->isStudent())
        {
            $groups = Yii::app()->user->getModel()->groups;
            if (count($groups)>0)
            {
                $timetable = array();
                foreach ($groups as $group)
                {
                    $curTerm = Term::model()->findByPk(Yii::app()->session['currentTerm']);
                    $idGroup = $group->id;
                    $criteria = new CDbCriteria();
                    $criteria->compare("idGroup",$idGroup);
                    $criteria->order = "time";
                    $criteria->compare("numWeek",$curTerm->getNumOfWeek());
                    $criteria->compare("day",date("w")-1);
                    $timetable = array_merge($timetable,Timetable::model()->findAll($criteria));
                }
            } else
            {
                $timetable = array();
            }
        } elseif (Yii::app()->user->isTeacher())
        {
            $curTerm = Term::model()->findByPk(Yii::app()->session['currentTerm']);
            $criteria = new CDbCriteria();
            $criteria->compare("teacher",Yii::app()->user->getFio());
            $criteria->order = "time";
            $criteria->compare("numWeek",$curTerm->getNumOfWeek());
            $criteria->compare("day",date("w")-1);
            $timetable = Timetable::model()->findAll($criteria);
            if ($timetable == null)
                $timetable = array();
        } else
        {
            $timetable = array();
        }
        ?>
        <?php if (count($timetable) == 0): ?>
            <div class="sidebar-small-item">
                <span>ЗАНЯТИЙ НЕТ</span>
            </div>
        <?php else:?>
            <?php foreach($timetable as $item):?>
                <div class="sidebar-small-item">
                    <span><?php echo $item->name;?></span>
                    <div class="description"><?php echo $item->time;?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
*/ ?>

<?
if (Yii::app()->user->isStudent()) {
    function groups_fn($i) {
        return $i->id_altstu;
    };

    $groups = array_map("groups_fn", Yii::app()->user->getModel()->groups);
}
?>

<?php if($groups): ?>
<textarea class="hide" id="schedule-groups"><?=trim(json_encode($groups))?></textarea>

<div class="sidebar-item">
    <div class="sidebar-title">
        Расписание
    </div>
    <div id="schedule-content" class="sidebar-content">
        <i class="fa fa-spinner fa-spin schedule-loader"></i>
    </div>
</div>
<?php endif; ?>