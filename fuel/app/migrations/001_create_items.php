<?php

namespace Fuel\Migrations;

class Create_items {

	public function up()
	{
		\DBUtil::create_table('items', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'title' => array('constraint' => 255, 'type' => 'varchar'),
			'price' => array('type' => 'float'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
			'points' => array('constraint' => 11, 'type' => 'int'),
			'category_id' => array('constraint' => 11, 'type' => 'int')
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('items');
	}
}