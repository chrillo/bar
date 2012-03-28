<?php
class Controller_Admin_Items extends Controller_Admin {
	
	public function action_index()
	{
		$categories=Model_Category::find('all',array('order_by'=>'order','related'=>array('items')));
		$items=Model_Item::find()->where('category_id', 0)->get();
		
		$uncategorized=Model_Category::factory(array(
			'label'=>'uncategorized'
		));
		$uncategorized->items=$items;
		$categories[]=$uncategorized;
				
		$data['categories']=$categories;
		
		$this->template->title = "Items";
		$this->template->content = View::factory('items/index', $data);
	}
	
	public function action_view($id = null)
	{
		$data['item'] = Model_Item::find($id);
		$this->template->title = "Item";
		$this->template->content = View::factory('items/view', $data);
	}
	
	public function action_create($id = null){
		if (Input::method() == 'POST'){
			$item = Model_Item::factory(array(
				'title' => Input::post('title'),
				'price' => Input::post('price'),
				'cost' => Input::post('cost'),
				'inventory' => Input::post('inventory'),
				'points' => 0,
				'category_id'=>Input::post('category_id')
			));

			try{
				$item->save();
				Session::set_flash('notice', $this->flash_success('Added item #' . $item->id . '.'));

				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {

				Session::set_flash('notice', $this->flash_error('Could not save item.'.$e->getMessage()));
			}
		}
		$data['categories']=$this->get_categories_selection();
		$this->template->title = "Items";
		$this->template->content = View::factory('items/create',$data);

	}
	
	public function action_edit($id = null){
		$item = Model_Item::find($id);

		if (Input::method() == 'POST'){
		
			$item->title = Input::post('title');
			$item->price = Input::post('price');
			$item->cost = Input::post('cost');
			$item->inventory = Input::post('inventory');
			$item->category_id=Input::post('category_id');

			try{
				$item->save();
				Session::set_flash('notice', $this->flash_success('Updated item #' . $id));
				Response::redirect('admin/items');
			}
            catch (Orm\ValidationFailed $e) {
				Session::set_flash('notice', $this->flash_error('Could not update item #' . $id));
			
			}
		}else{
			$this->template->set_global('item', $item, false);
		}
		$data['categories']=$this->get_categories_selection();
		$this->template->title = "Items";
		$this->template->content = View::factory('items/edit',$data);

	}
	
	public function action_delete($id = null){
		if ($item = Model_Item::find($id)){
			$item->delete();
			Session::set_flash('notice', $this->flash_success('Deleted item #' . $id));
		}else{
			Session::set_flash('notice', $this->flash_error('Could not delete item #' . $id));
		}
		Response::redirect('admin/items');
	}
	private function get_categories_selection(){
		$models=Model_Category::find('all');
		$categories=array();
		foreach($models as $model){
			$categories[$model->id]=$model->label;
		}
		return $categories;
	}
	
}

/* End of file items.php */
