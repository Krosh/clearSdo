<?php

class m151005_135756_add_accessrules_to_learnmaterials extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `tbl_accesscontrolmaterials` ADD `isLearnMaterial` TINYINT(2) NOT NULL;";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
	}

	public function down()
	{
		echo "m151005_135756_add_accessrules_to_learnmaterials does not support migration down.\n";
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