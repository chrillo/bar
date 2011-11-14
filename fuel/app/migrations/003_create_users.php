<?php

namespace Fuel\Migrations;

class Create_users {

	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'username' => array('constraint' => 255, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'email' => array('constraint' => 255, 'type' => 'varchar'),
			'profile_fields' => array('type' => 'text'),
			'group' => array('constraint' => 11, 'type' => 'int'),
			'last_login' => array('constraint' => 20, 'type' => 'int'),
			'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
			'pin' => array('constraint' => 8, 'type' => 'varchar')
		), array('id'));
		

		$user = \Model_User::factory(array(
				'username' => 'admin',
				'password' => \Auth::instance()->hash_password('password'),
				'email' => 'chrillo.at@gmail.com',
				'pin' => '1234',
				'profile_fields' => '',
				'group' =>  '',
				'last_login' =>  '',
				'login_hash' =>'',
			));
		$user->save();
		
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}