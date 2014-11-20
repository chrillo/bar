<?php

namespace Fuel\Migrations;

class Add_Cost_Proft_Category_To_Consumptions {

	public function up()
	{
    \DBUtil::add_fields('consumptions', array(
						'cost' => array('type' => 'float'),
						'profit' => array('type' => 'float'),
						'category' => array('constraint' => 255, 'type' => 'varchar'),


    ));
	}

	public function down()
	{
    \DBUtil::drop_fields('categories', array(
			'cost',
			'profit',
			'category'
    ));
	}
}