<?php



class ValidationFailed extends \Fuel_Exception {}


class Observer_Unique extends \Orm\Observer
{

	public function before_insert($obj){
		
		if($this->is_unique($obj,"users",array("pin","email","username"))){
			return $obj;
		}else{
			throw new Orm\ValidationFailed("not unique");
		};
	}
	public function before_save($obj){
		
		if($this->is_unique($obj,"users",array("pin","email","username"))){
			return $obj;
		}else{
			throw new Orm\ValidationFailed("not unique");
		};

	}

    public function is_unique($obj,$table,$fields)
    {
		$query = DB::select('*')->from('users');
		$query->where("id","!=",$obj->id);
		$query->and_where_open();
		foreach($fields as $field){
           $query->or_where($field, '=', Str::lower($obj->$field));
        }
        $query->and_where_close();
        $query->from($table);
		$result=$query->execute();
        return ! ($result->count() > 0);
    }
	
}
?>