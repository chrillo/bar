<?php

class Controller_Admin extends Controller_Template {

    public function before()
    {
    	parent::before();
        if (!Auth::check()  &&  Request::active()->action!="login" )
		{
			Response::redirect('admin/login');
		}

    }

    // your methods

    public function action_index()
    {
    	Response::redirect('admin/dashboard');


    }
	public function action_login()
    {
    	$data = array();
    	 $auth = Auth::instance();
    		if(Auth::check()){
					Response::redirect('admin');
					return;
			}
			

    		if($_POST)
    		{


    		    // check the credentials. This assumes that you have the previous table created
    		    if($auth->login($_POST['username'],$_POST['password']))
    		    {
    		        // credentials ok, go right in
    		        Response::redirect('admin');
    		    }
    		    else
    		    {
    		        // Oops, no soup for you. try to login again.
    		        // Set some values to repopulate the username field and give some error text back to the view

    		        $data['username']    = $_POST['username'];
    		        $data['errors'] = 'Wrong username/password combo. Try again';
    		    }
    		}

    		// Show the login form
    	$this->template->title="";
    	$this->template->content = View::factory('admin/login',$data);
    }

    public function action_logout(){
    	 Auth::instance()->logout();
    	Response::redirect('admin/login');
    }
    public function flash_success($text){
    	return "<p class='alert-message success'>".$text."</p>";
    }
	public function flash_error($text){
		return "<p class='alert-message error'>".$text."</p>";
	}


}

?>