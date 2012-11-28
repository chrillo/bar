<?php

namespace Fuel\Migrations;

class Add_fristnamelastname_to_users {

	public function up()
	{
    \DBUtil::add_fields('users', array(
						'firstname' => array('constraint' => 255, 'type' => 'varchar'),
			'lastname' => array('constraint' => 255, 'type' => 'varchar'),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('users', array(
			'firstname','lastname'    
    ));
	}
}