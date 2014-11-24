<?php

namespace Fuel\Migrations;

class Add_status_to_items {

	public function up()
	{
    \DBUtil::add_fields('items', array(
						'status' => array('constraint' => 255, 'type' => 'varchar'),


    ));
	}

	public function down()
	{
    \DBUtil::drop_fields('items', array(
			'status'
    ));
	}
}