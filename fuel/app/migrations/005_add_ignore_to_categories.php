<?php

namespace Fuel\Migrations;

class Add_ignore_to_categories {

	public function up()
	{
    \DBUtil::add_fields('categories', array(
						'ignore' => array('constraint' => 11, 'type' => 'int','default'=>0),

    ));	
	}

	public function down()
	{
    \DBUtil::drop_fields('categories', array(
			'ignore'    
    ));
	}
}