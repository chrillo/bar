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
        'saldo' => array(
        	'type' =>'int',
        	'label' => 'Saldo',
    		'default' =>0
        ),
        'maxed' => array(
        	'type' =>'int',
        	'label' => 'Maxed',
    		'default' =>0
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
	public function update_saldo(){
		$query = DB::query('SELECT *, SUM(price) as saldo FROM consumptions WHERE user_id='.$this->id.' AND STATUS=1');
		$result=$query->execute()->as_array();
		$saldo=$result[0]['saldo'];
		
		$this->saldo=$saldo;
		
		$query = DB::query('SELECT *, COUNT(item_id) as perItem FROM consumptions WHERE user_id='.$this->id.' AND STATUS=1 GROUP BY item_id');
		$perItems=$query->execute()->as_array();
		
		$items_ids = array();
		$count_cache = array();
		foreach($perItems as $perItem){
			$item_ids[]=$perItem['item_id'];
			$count_cache[$perItem['item_id']]=$perItem['perItem'];
		}
		$items = Model_Item::find('all');
		$maxed = 0;
		
		foreach($items as $item){
			if(isset($count_cache[$item->id])){
				if($item->maxusage>0 && $count_cache[$item->id] >$item->maxusage ){
					$maxed = 1;
				}
			}
		}

		$this->maxed = $maxed;
		

		$this->save();
	
		return $saldo;
	}
	
}

/* End of file user.php */