<?php
class FrontendHomeController extends AdminController {

	public function index()
	{
		$view_home = View::make('admin.layout.home');
		$content = $view_home->render();
		$arr_data = [
			'content' => $content
		];
		$this->layout->content = View::make('admin.frontend-home')
										->with($arr_data);
	}
	public function source(){
		$view_home = View::make('admin.layout.home');
		$content = $view_home->render();
		return View::make('admin.layout.content-builder')->with([
					'content'=>$content
			]);
	}

	public function update(){
		$content = Input::has('content') ? e(Input::get('content')) : '';
		$content = html_entity_decode($content);
		file_put_contents(app_path()."/views/admin/layout/home.blade.php", $content);
		$message = 'Homepage has been updated successful';
		return Redirect::to('/admin/frontend-home')->with('flash_success',"{$message}.");
	}
}