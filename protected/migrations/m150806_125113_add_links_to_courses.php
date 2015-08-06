<?php

class m150806_125113_add_links_to_courses extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `forum`
ADD COLUMN `idCourse` INT NULL AFTER `is_locked`;
ALTER TABLE `tbl_learnmaterials`
CHANGE COLUMN `path` `path` TEXT NULL ,
CHANGE COLUMN `title` `title` TEXT NULL DEFAULT NULL ;

";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
	}

	public function down()
	{
		echo "m150806_125113_add_links_to_courses does not support migration down.\n";
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