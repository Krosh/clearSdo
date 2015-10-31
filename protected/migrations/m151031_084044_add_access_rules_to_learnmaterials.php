<?php

class m151031_084044_add_access_rules_to_learnmaterials extends CDbMigration
{
	public function up()
	{
        $sql = "
        CREATE TABLE IF NOT EXISTS `tbl_accesslearnmaterials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_relation` int(11) DEFAULT NULL,
  `idRecord` int(11) DEFAULT NULL,
  `idCourse` int(11) DEFAULT NULL,
  `idLearnMaterial` int(11) DEFAULT NULL,
  `accessType` int(11) DEFAULT NULL,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `idBeforeMaterial` int(11) DEFAULT NULL,
  `minMark` int(11) DEFAULT NULL,
  `addTryes` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
        $command = $this->dbConnection->createCommand($sql);
        $command->execute();

    }

	public function down()
	{
		echo "m151031_084044_add_access_rules_to_learnmaterials does not support migration down.\n";
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