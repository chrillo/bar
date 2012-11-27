<?php

namespace Fuel\Migrations;

class Add_countcache_to_users {

	public function up()
	{
    \DBUtil::add_fields('users', array(
						'countcache' => array('type' => 'text'),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('users', array(
			'countcache'    
    ));
	}
}