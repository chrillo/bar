<?php

namespace Fuel\Migrations;

class Add_saldo_to_users {

	public function up()
	{
    \DBUtil::add_fields('users', array(
						'saldo' => array('type' => 'float'),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('users', array(
			'saldo'    
    ));
	}
}