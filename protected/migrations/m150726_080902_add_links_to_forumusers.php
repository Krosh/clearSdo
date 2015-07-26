<?php

class m150726_080902_add_links_to_forumusers extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `tbl_users`
ADD COLUMN `idForumUser` INT NULL AFTER `birthday`;
";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

	public function down()
	{
		echo "m150726_080902_add_links_to_forumusers does not support migration down.\n";
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