<?php

class FrontendPagesController extends \BaseController {

	public function index($pageName)
	{
		$page = Page::where('short_name', $pageName)
				->where('active', 1)
				->first();				
		if( !is_object($page) ) {
			return App::abort(404);
		}

		$page = $page->toArray();

		$this->layout->metaInfo['meta_title'] = $page['meta_title'];
		$this->layout->metaInfo['meta_description'] = $page['meta_description'];
		$this->layout->content = View::make('frontend.pages')->with([
																	'page' => $page
																]);
	}
	public function listproduct(){
		$products = Product::where('active',1)->get();		
		foreach($products as $product){
			echo '<div>';
				echo '<strong>';
					echo $product->name;
				echo '</strong>';
				echo '<br /> Short name:'.$product->short_name;
				echo '<br /> Sku:'.$product->sku;
			echo '</div>';
			echo '<br />';
		}
		die;
	}
	public function testmath(){
		$pointA = array('x'=>8,'y'=>9);
		$pointB = array('x'=>5,'y'=>7);
		$pointC = array('x'=>15,'y'=>8);
		$perpendicular_line = DesignOnline::linear_equations($pointA,$pointB,"perpendicular");
		pr($perpendicular_line);
		$line = DesignOnline::linear_equations($pointA,$pointB);
		pr($line);
		$parallel_line = DesignOnline::linear_parallel($line,10);
		pr($parallel_line);
		foreach ($parallel_line as $key => $p_line) {
			$node = DesignOnline::coor_node($perpendicular_line,$p_line);
			pr($node);
			if(DesignOnline::two_point_same_located($line,$pointC,$node)) //neu cung phia
				continue;
			// else{
				$result = $node; break;
			// }

		}

		pr($result);

		die;

	}

}