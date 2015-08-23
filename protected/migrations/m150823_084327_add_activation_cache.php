<?php

class m150823_084327_add_activation_cache extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `tbl_users`
ADD COLUMN `activationCache` VARCHAR(64) NULL AFTER `defaultLanguage`;

";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
	}

	public function down()
	{
		echo "m150823_084327_add_activation_cache does not support migration down.\n";
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