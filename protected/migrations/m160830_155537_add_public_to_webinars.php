<?php

class m160830_155537_add_public_to_webinars extends CDbMigration
{
	public function up()
	{
        $this->addColumn("tbl_webinars","isPublic","BOOLEAN");
        $this->addColumn("tbl_webinars","password","VARCHAR(60)");
	}

	public function down()
	{
		echo "m160830_155537_add_public_to_webinars does not support migration down.\n";
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