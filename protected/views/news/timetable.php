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
        <?php foreach($timetable as $item):?>
            <div class="sidebar-small-item">
                <span><?php echo $item->name;?></span>
                <div class="description"><?php echo $item->time;?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
