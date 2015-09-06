<?php

class m150906_133743_add_faculties extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `tbl_groups` ADD `faculty` VARCHAR(256) NOT NULL;";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

	public function down()
	{
		echo "m150906_133743_add_faculties does not support migration down.\n";
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