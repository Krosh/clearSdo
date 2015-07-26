<?php

class m150725_161117_add_date_action extends CDbMigration
{
	public function up()
	{
        $sql = "ALTER TABLE `tbl_coursescontrolmaterials`
ADD COLUMN `dateAction` DATE NULL AFTER `dateAdd`";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

	public function down()
	{
		echo "m150725_161117_add_date_action does not support migration down.\n";
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