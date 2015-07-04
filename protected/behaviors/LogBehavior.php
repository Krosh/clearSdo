<?php
/**
 * Created by JetBrains PhpStorm.
 * User: БОСС
 * Date: 04.07.15
 * Time: 13:08
 * To change this template use File | Settings | File Templates.
 */
define("LOG_CREATE",1);
define("LOG_UPDATE",2);
define("LOG_DELETE",3);

class LogBehavior extends CActiveRecordBehavior
{
    public $modelClass = "Log";
    public $tableName;

    public function afterSave($event)
    {
        if ($this->owner->isNewRecord)
        {
            $this->addLogRecord(LOG_CREATE);
        } else
        {
            $this->addLogRecord(LOG_UPDATE);
        }
    }

    public function beforeDelete($event)
    {
        $this->addLogRecord(LOG_DELETE);
    }

    private function addLogRecord($idAction)
    {
        $logRecord = new $this->modelClass;
        $logRecord->idUser = Yii::app()->user->getId();
        $logRecord->tableName = $this->tableName;
        $logRecord->idAction = $idAction;
        $logRecord->idRecord = $this->owner->primaryKey;
        $logRecord->dateAction = date("Y-m-d H:i:s");
        $logRecord->save();
    }

}