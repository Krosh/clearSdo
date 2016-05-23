<?php

class m160523_105418_add_fields_to_groups extends CDbMigration
{
	public function up()
	{
        $sql = "
          ALTER TABLE `tbl_groups`
          ADD `status` INT(2) NOT NULL DEFAULT 0,
          ADD `form_teaching` INT(2) NOT NULL DEFAULT 0;
          UPDATE `tbl_groups` SET status = 1, form_teaching = 1;
          ";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();
    }

	public function down()
	{
		echo "m160523_105418_add_fields_to_groups does not support migration down.\n";
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