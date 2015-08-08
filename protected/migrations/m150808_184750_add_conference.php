<?php

class m150808_184750_add_conference extends CDbMigration
{
	public function up()
	{
        $sql = "
CREATE TABLE `tbl_conference` (
`id` INT NOT NULL AUTO_INCREMENT,
`idConference` INT NULL,
`idUser` INT NULL,
PRIMARY KEY (`id`));

ALTER TABLE `tbl_messages`
ADD COLUMN `isConference` TINYINT(1) NULL AFTER `text`,
ADD COLUMN `isService` TINYINT(1) NULL AFTER `isConference`,
ADD COLUMN `isPublishedOnMain` TINYINT(1) NULL AFTER `isService`;
";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
   }


    public function down()
	{
		echo "m150808_184750_add_conference does not support migration down.\n";
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