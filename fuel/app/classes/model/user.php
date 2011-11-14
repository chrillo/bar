<?php

class Model_User extends Orm\Model {
	 protected static $_properties = array(
        'id' => array('type' => 'int'),
        'username' => array(
            'type' => 'varchar',
            'label' => 'Username',
            'validation' => array('required', 'min_length' => array(3), 'max_length' => array(20))
        ),
        'password' => array(
            'type' => 'varchar',
            'label' => 'Password',
            'validation' => array('required', 'min_length' => array(3), 'max_length' => array(48))
        ),
        'pin' => array(
            'type' => 'varchar',
            'label' => 'Pin',
            'validation' => array('required', 'min_length' => array(4), 'max_length' => array(8))
        ),
         'email' => array(
            'type' => 'varchar',
            'label' => 'Email',
            'validation' => array('required','valid_email')
        ),
        'profile_fields' => array('type' => 'text', 'label' => 'Profile fields'),
        'last_login' => array('type' => 'int', 'label' => 'Last login'),
        'created_at' => array('type' => 'int', 'label' => 'Created At'),
        'updated_at' => array('type' => 'int', 'label' => 'Updated At'),
        'group' => array('type' => 'int', 'label' => 'Group'),
        'login_hash' => array('type' => 'varchar', 'label' => 'Login Hash')
    );
	protected static $_observers = array(
		'Unique' => array('before_insert','before_save'),
		'Orm\\Observer_CreatedAt' => array('before_insert'),
		'Orm\\Observer_UpdatedAt' => array('before_save'),
		'Orm\\Observer_Validation' => array('before_save')
	);
}

/* End of file user.php */