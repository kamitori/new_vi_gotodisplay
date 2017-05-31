<?php

class MenuTableSeeder extends Seeder {

	public function run()
	{
		Menu::create([
			'name' => 'Dashboard',
			'link' => 'admin',
			'icon_class'	=> 'icon-home'
		]);

		Menu::create([
			'name' => 'Configure',
			'link' => 'admin/configures',
			'icon_class'	=> 'fa fa-cogs'
		]);

		Menu::create([
			'name' => 'Banner',
			'link' => 'admin/banners',
			'icon_class'	=> 'icon-credit-card'
		]);

		Menu::create([
			'name' => 'Menu',
			'link' => 'admin/menus',
			'icon_class'	=> 'icon-list'
		]);

		Menu::create([
			'name' => 'User',
			'link' => 'admin/users',
			'icon_class'	=> 'icon-users'
		]);

		Menu::create([
			'name' => 'Admin',
			'link' => 'admin/admins',
			'icon_class'	=> 'fa fa-users'
		]);

		Menu::create([
			'id'  	=> 7,
			'name' 	=> 'Products Group',
			'icon_class'	=> 'icon-social-dropbox'
		]);

		Menu::create([
			'name' => 'Product',
			'link' => 'admin/products',
			'icon_class'	=> 'icon-bag',
			'parent_id' => 7
		]);

		Menu::create([
			'name' => 'Option Group',
			'link' => 'admin/product-option-groups',
			'icon_class'	=> 'icon-notebook',
			'parent_id' => 7,
			'order_no'  => 2
		]);

		Menu::create([
			'name' => 'Option',
			'link' => 'admin/product-options',
			'icon_class'	=> 'icon-layers',
			'parent_id' => 7,
			'order_no'  => 3
		]);

		Menu::create([
			'name' => 'Category',
			'link' => 'admin/product-categories',
			'icon_class'	=> 'icon-grid',
			'parent_id' => 7,
			'order_no'  => 4
		]);

		Menu::create([
			'name' => 'Layout',
			'link' => 'admin/layouts',
			'icon_class'	=> 'icon-screen-tablet',
			'parent_id' => 7,
			'order_no'  => 5
		]);

		Menu::create([
			'id'   => 13,
			'name' => 'Pages',
			'icon_class'	=> 'icon-docs'
		]);

		Menu::create([
			'name' => 'Static Page',
			'link' => 'admin/pages',
			'icon_class'	=> 'icon-doc',
			'parent_id' => 13,
			'order_no'  => 1
		]);

	}

}