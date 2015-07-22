<?php

class m150722_100723_add_showtime_to_answers extends CDbMigration
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
		echo "m150722_100723_add_showtime_to_answers does not support migration down.\n";
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