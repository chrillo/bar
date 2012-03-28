<?php

namespace Fuel\Migrations;

class Add_cost_to_items {

	public function up()
	{
    \DBUtil::add_fields('items', array(
						'cost' => array('type' => 'float'),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('items', array(
			'cost'    
    ));
	}
}