<?php

class Model_Item extends Orm\Model {
	protected static $_belongs_to = array('category');
	protected static $_properties = array(
        'id' => array('type' => 'int'),
        'title' => array(
            'type' => 'varchar',
            'label' => 'Label',
            'validation' => array('required', 'min_length' => array(3), 'max_length' => array(20))
        ),
        'price' => array(
            'type' => 'float',
            'label' => 'Price',
            'validation' => array('required')
        ),
        'cost' => array(
            'type' => 'float',
            'label' => 'Cost',
            'validation' => array('required')
        ),
        'inventory' => array(
            'type' => 'float',
            'label' => 'Cost',
            'validation' => array('required')
        ),
        'maxusage' => array(
            'type' => 'int',
            'label' => 'Max Usage',
        ),
        'points' => array(
            'type' => 'int',
            'label' => 'Points'
        ),
        'category_id' => array(
            'type' => 'int',
            'label' => 'Category',
            'validation' => array('required')
        ),
        'status' => array(
            'type' =>'varchar',
            'label' => 'Status',
            'default' =>''
        ),

        'created_at' => array('type' => 'int', 'label' => 'Created At'),
        'updated_at' => array('type' => 'int', 'label' => 'Updated At')
    );

	protected static $_observers = array(
		'Orm\\Observer_CreatedAt' => array('before_insert'),
		'Orm\\Observer_UpdatedAt' => array('before_save'),
		'Orm\\Observer_Validation' => array('before_save')
	);
}

/* End of file item.php */