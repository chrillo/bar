<?php

namespace Fuel\Migrations;

class Create_consumptions {

	public function up()
	{
		\DBUtil::create_table('consumptions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'item_id' => array('constraint' => 11, 'type' => 'int'),
			'price' => array('type' => 'float'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
			'status' => array('constraint' => 11, 'type' => 'int'),
			'title' => array('constraint' => 255, 'type' => 'varchar'),
			'order_id' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('consumptions');
	}
}