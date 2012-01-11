<?php
class Controller_Admin_Categories extends Controller_Admin {
	
	public function action_index()
	{
		$data['categories'] = Model_Category::find('all',array('order_by'=>'order','related'=>array('items')));
		$this->template->title = "Categories";
		$this->template->content = View::factory('categories/index', $data);

	}
	
	public function action_view($id = null)
	{
		$data['category'] = Model_Category::find($id);
		
		$this->template->title = "Category";
		$this->template->content = View::factory('categories/view', $data);

	}
	
	public function action_create($id = null)
	{
		if (Input::method() == 'POST')
		{
			$user=Auth::instance()->get_user_array(array('user_id'));

			$category = Model_Category::factory(array(
				'label' => Input::post('label'),
				'user_id' => $user['user_id'][1],
				'ignore'=>Input::post('ignore'),
				'order'=> Input::post('order')
			));

			try{
			
				$category->save();
				Session::set_flash('notice',$this->flash_success('Added category #' . $category->id . '.'));

				Response::redirect('admin/items');
		

			}
            catch (Orm\ValidationFailed $e) {
				Session::set_flash('notice',  $this->flash_error('Could not save category.'));
			}
		}


		$this->template->title = "Categories";
		$this->template->content = View::factory('categories/create');

	}
	
	public function action_edit($id = null)
	{
		$category = Model_Category::find($id);
		$user=Auth::instance()->get_user_array(array('user_id'));
		
		if (Input::method() == 'POST')
		{
			$category->label = Input::post('label');
			$category->user_id = $user['user_id'][1];
			$category->order =Input::post('order');
			$category->ignore=Input::post('ignore',0);
		
			try{
				$category->save();
				Session::set_flash('notice',$this->flash_success('Updated category #' . $id));

				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {
			
				Session::set_flash('notice',  $this->flash_error('Could not update category #' . $id));
			}
		}else{
			$this->template->set_global('category', $category, false);
		}
		
		$this->template->title = "Categories";
		$this->template->content = View::factory('categories/edit');

	}
	
	public function action_delete($id = null)
	{
		if ($category = Model_Category::find($id))
		{
			$query = DB::update('items');
			$query->where('category_id','=',$id);
			$query->value('category_id', 0);
			$query->execute();
			$category->delete();
			
			Session::set_flash('notice', $this->flash_success('Deleted category #' . $id));
		}

		else
		{
			Session::set_flash('notice',  $this->flash_error('Could not delete category #' . $id));
		}

		Response::redirect('admin/items');

	}
	
	
}

/* End of file categories.php */
