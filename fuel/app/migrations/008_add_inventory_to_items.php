<?php

namespace Fuel\Migrations;

class Add_inventory_to_items {

	public function up()
	{
    \DBUtil::add_fields('items', array(
						'inventory' => array('constraint' => 11, 'type' => 'int'),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('items', array(
			'inventory'    
    ));
	}
}