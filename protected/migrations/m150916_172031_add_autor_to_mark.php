<?php

class m150916_172031_add_autor_to_mark extends CDbMigration
{
	public function up()
	{
        $sql = "
ALTER TABLE `tbl_userscontrolmaterials` ADD `idAutorMark` INT(11) NOT NULL;";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

	public function down()
	{
		echo "m150916_172031_add_autor_to_mark does not support migration down.\n";
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