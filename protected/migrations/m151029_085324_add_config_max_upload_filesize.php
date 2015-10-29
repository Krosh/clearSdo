<?php

class m151029_085324_add_config_max_upload_filesize extends CDbMigration
{
	public function up()
	{
        $sql = "
        ALTER TABLE  `tbl_config` ADD  `maxUploadFilesize` INT( 11 ) NULL AFTER  `activeTimezone`";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
	}

	public function down()
	{
		echo "m151029_085324_add_config_max_upload_filesize does not support migration down.\n";
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