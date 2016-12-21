<?php

class m161221_062413_comment extends CDbMigration
{
	public function safeUp() {
		$this->createTable(
			'comment',
			array(
				'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'user_id' => 'int(11) UNSIGNED NOT NULL',
				'post_id' => 'int(11) UNSIGNED NOT NULL',
				'content' => 'varchar(255) NOT NULL',
				'created_at' => 'int(11)',
				'updated_at' => 'int(11)',
				'status' => 'TINYINT(1)',
				'PRIMARY KEY (id)',
			),
			'ENGINE=InnoDB'
		);
	}

	public function safeDown() {

		$this->dropTable("comment");

	}
}