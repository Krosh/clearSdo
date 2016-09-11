<?php

class m150728_082013_add_language_to_users extends CDbMigration
{
	public function up()
	{

        $sql = "
 ALTER TABLE `tbl_users`
ADD COLUMN `defaultLanguage` VARCHAR(45) NULL AFTER `idForumUser`;
";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();

	}

	public function down()
	{
		echo "m150728_082013_add_language_to_users does not support migration down.\n";
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