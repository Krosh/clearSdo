<?php

class m150903_130648_create_search_index extends CDbMigration
{
	public function up()
	{
        $sql = "
CREATE TABLE `tbl_searchindex` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `idTable` INT NULL,
  `content` TEXT NULL,
  PRIMARY KEY (`id`));
ALTER TABLE `tbl_searchindex`
ADD COLUMN `idRecord` INT NULL AFTER `content`;

";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
	}

	public function down()
	{
		echo "m150903_130648_create_search_index does not support migration down.\n";
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