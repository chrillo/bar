<?php

namespace Fuel\Migrations;

class Add_maxed_to_users {

	public function up()
	{
    \DBUtil::add_fields('users', array(
						'maxed' => array('constraint' => 11, 'type' => 'int'),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('users', array(
			'maxed'    
    ));
	}
}