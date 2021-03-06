<?php

class m150720_160836_add_date_change_password extends CDbMigration
{
    public function up()
    {
        $sql = "ALTER TABLE `tbl_usersanswers`
ADD COLUMN `showTime` DATETIME NULL AFTER `answerTime`;
";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

    public function down()
    {
        echo "m150720_160836_add_date_change_password does not support migration down.\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}