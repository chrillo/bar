<?php 

class Controller_Admin_Dashboard extends Controller_Admin {

	public function action_index(){
		
		$this->template->title="Dashboard";
        
     	$this->template->title = "Items";

       	$days=365;
        $data=array();
        $sales=$this->get_sales($days);
        $paid=0;
        $paidCount=0;
        $unpaid=0;
        $unpaidCount=0;
        $total=0;
        $totalCount=0;
        
        foreach($sales as $sale){
        	if($sale->status>1){
        		$paid+=$sale->price;
        		$paidCount++;
        	}else{
        		$unpaid+=$sale->price;
        		$unpaidCount++;
        	}
        	$totalCount++;
        	$total+=$sale->price;
        }
        
        $data['days']=$days;
        $data['total']=$total;
        $data['totalCount']=$totalCount;
       	$data['paid']=$paid;
       	$data['paidCount']=$paidCount;
       	$data['unpaid']=$unpaid;
       	$data['unpaidCount']=$unpaidCount;
       	
       	
       	$data['users']=DB::count_records('users');
       	
        $this->template->content=View::factory('admin/dashboard',$data);
	}
	private function get_sales($days){
		return $consumptions=DB::select()->from('consumptions')->as_object('Model_Consumption')->where('created_at','>=',(time()-$days*24*3600))->and_where('status','!=',0)->execute()->as_array();
	}
}

?>