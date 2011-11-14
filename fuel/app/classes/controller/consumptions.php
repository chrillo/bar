<?php
class Controller_Consumptions extends Controller_Template {
	
	public function action_index()
	{
		$data['consumptions'] = Model_Consumption::find('all');
		$this->template->title = "Consumptions";
		$this->template->content = View::factory('consumptions/index', $data);

	}
	
	public function action_view($id = null)
	{
		$data['consumption'] = Model_Consumption::find($id);
		
		$this->template->title = "Consumption";
		$this->template->content = View::factory('consumptions/view', $data);

	}
	
	public function action_create($id = null)
	{
		if (Input::method() == 'POST')
		{
			$consumption = Model_Consumption::factory(array(
				'user_id' => Input::post('user_id'),
				'item_id' => Input::post('item_id'),
				'price' => Input::post('price'),
			));

			if ($consumption and $consumption->save())
			{
				Session::set_flash('notice', 'Added consumption #' . $consumption->id . '.');

				Response::redirect('consumptions');
			}

			else
			{
				Session::set_flash('notice', 'Could not save consumption.');
			}
		}

		$this->template->title = "Consumptions";
		$this->template->content = View::factory('consumptions/create');

	}
	
	public function action_edit($id = null)
	{
		$consumption = Model_Consumption::find($id);

		if (Input::method() == 'POST')
		{
			$consumption->user_id = Input::post('user_id');
			$consumption->item_id = Input::post('item_id');
			$consumption->price = Input::post('price');

			if ($consumption->save())
			{
				Session::set_flash('notice', 'Updated consumption #' . $id);

				Response::redirect('consumptions');
			}

			else
			{
				Session::set_flash('notice', 'Could not update consumption #' . $id);
			}
		}
		
		else
		{
			$this->template->set_global('consumption', $consumption, false);
		}
		
		$this->template->title = "Consumptions";
		$this->template->content = View::factory('consumptions/edit');

	}
	
	public function action_delete($id = null)
	{
		if ($consumption = Model_Consumption::find($id))
		{
			$consumption->delete();
			
			Session::set_flash('notice', 'Deleted consumption #' . $id);
		}

		else
		{
			Session::set_flash('notice', 'Could not delete consumption #' . $id);
		}

		Response::redirect('consumptions');

	}
	
	
}

/* End of file consumptions.php */
