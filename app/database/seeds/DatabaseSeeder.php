<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('AdminTableSeeder');
		// $this->call('UserTableSeeder');
		// $this->call('MenuTableSeeder');
		// $this->call('ProductTableSeeder');
		// $this->call('CategoryTableSeeder');
		// $this->call('OptionTableSeeder');
		// $this->call('OptionGroupTableSeeder');
		// $this->call('TypeTableSeeder');
		// $this->call('LayoutTableSeeder');
		// $this->call('LayoutDetailTableSeeder');
		// $this->call('BannerTableSeeder');
		// $this->call('ConfigureTableSeeder');
		// $this->call('PageTableSeeder');
		// $this->call('ImageTableSeeder');
		// $this->call('ImageableTableSeeder');
		//  ======== new Seed =======
		$this->call('AdminsTableSeeder');
		$this->call('BannersTableSeeder');
		$this->call('CategoriesTableSeeder');
		$this->call('ConfiguresTableSeeder');
		$this->call('ImageablesTableSeeder');
		$this->call('ImagesTableSeeder');
		$this->call('LayoutsTableSeeder');
		$this->call('LayoutDetailsTableSeeder');
		$this->call('MenusTableSeeder');
		$this->call('OptionablesTableSeeder');
		$this->call('OptionsTableSeeder');
		$this->call('OptionGroupsTableSeeder');
		$this->call('PagesTableSeeder');
		$this->call('PriceBreaksTableSeeder');
		$this->call('ProductsTableSeeder');
		$this->call('ProductsCategoriesTableSeeder');
		$this->call('SizeListsTableSeeder');
		$this->call('TypesTableSeeder');
		$this->call('UsersTableSeeder');
		$this->call('ContactsTableSeeder');
		$this->call('RolesTableSeeder');
		$this->call('PermissionsTableSeeder');
		$this->call('PermissionRoleTableSeeder');
		$this->call('AssignedRolesTableSeeder');
		$this->call('OrdersTableSeeder');
		$this->call('OrderDetailsTableSeeder');
		Cache::flush();
		// BackgroundProcess::copyFromVI();
	}

}
