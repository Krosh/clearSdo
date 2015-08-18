<?php

class m150818_100450_add_read_notices extends CDbMigration
{
	public function up()
	{
        $sql = "
CREATE TABLE `tbl_readednotices` (
  `id` INT NULL AUTO_INCREMENT,
  `idMessage` INT NULL,
  `idUser` INT NULL,
  `isReaded` TINYINT(1) NULL,
  PRIMARY KEY (`id`));
";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
	}

	public function down()
	{
		echo "m150818_100450_add_read_notices does not support migration down.\n";
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