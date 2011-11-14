<?php

class Model_Category extends Orm\Model {
	protected static $_belongs_to = array('user');
	protected static $_has_many = array('items');
	protected static $_properties = array(
        'id' => array('type' => 'int'),
        'label' => array(
            'type' => 'varchar',
            'label' => 'Label',
            'validation' => array('required', 'min_length' => array(3), 'max_length' => array(20))
        ),
        'user_id' => array(
            'type' => 'int',
            'label' => 'User',
            'validation' => array('required')
        ),
        'order' => array('type' => 'int', 'label' => 'Order'),
        'created_at' => array('type' => 'int', 'label' => 'Created At'),
        'updated_at' => array('type' => 'int', 'label' => 'Updated At')
    );


	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array('before_insert'),
		'Orm\\Observer_UpdatedAt' => array('before_save'),
		'Orm\\Observer_Validation' => array('before_save')
	);
}

/* End of file category.php */