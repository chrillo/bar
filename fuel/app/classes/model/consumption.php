<?php

class Model_Consumption extends Orm\Model {
	protected static $_has_one = array('user','item');
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array('before_insert'),
		'Orm\Observer_UpdatedAt' => array('before_save'),
	);
}

/* End of file consumption.php */