<?php

class m150915_172031_add_confirm_change_email extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `tbl_users` ADD `new_email` VARCHAR(1024) NOT NULL;";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

	public function down()
	{
		echo "m150915_172031_add_confirm_change_email does not support migration down.\n";
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