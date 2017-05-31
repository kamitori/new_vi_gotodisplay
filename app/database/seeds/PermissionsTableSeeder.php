<?php

class PermissionsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('permissions')->delete();

		\DB::table('permissions')->insert(array (
			0 =>
			array (
				'id' => 1,
				'name' => 'admin_view_all',
				'display_name' => 'View All Admin',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			1 =>
			array (
				'id' => 2,
				'name' => 'admins_view_all',
				'display_name' => 'View All Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			2 =>
			array (
				'id' => 3,
				'name' => 'admins_view_owner',
				'display_name' => 'View Owner Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			3 =>
			array (
				'id' => 4,
				'name' => 'admins_create_owner',
				'display_name' => 'Create Owner Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			4 =>
			array (
				'id' => 5,
				'name' => 'admins_edit_all',
				'display_name' => 'Edit All Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			5 =>
			array (
				'id' => 6,
				'name' => 'admins_edit_owner',
				'display_name' => 'Edit Owner Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			6 =>
			array (
				'id' => 7,
				'name' => 'admins_delete_all',
				'display_name' => 'Delete All Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			7 =>
			array (
				'id' => 8,
				'name' => 'admins_delete_owner',
				'display_name' => 'Delete Owner Admins',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			8 =>
			array (
				'id' => 9,
				'name' => 'banners_view_all',
				'display_name' => 'View All Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			9 =>
			array (
				'id' => 10,
				'name' => 'banners_view_owner',
				'display_name' => 'View Owner Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			10 =>
			array (
				'id' => 11,
				'name' => 'banners_create_owner',
				'display_name' => 'Create Owner Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			11 =>
			array (
				'id' => 12,
				'name' => 'banners_edit_all',
				'display_name' => 'Edit All Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			12 =>
			array (
				'id' => 13,
				'name' => 'banners_edit_owner',
				'display_name' => 'Edit Owner Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			13 =>
			array (
				'id' => 14,
				'name' => 'banners_delete_all',
				'display_name' => 'Delete All Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			14 =>
			array (
				'id' => 15,
				'name' => 'banners_delete_owner',
				'display_name' => 'Delete Owner Banners',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			15 =>
			array (
				'id' => 16,
				'name' => 'configures_view_all',
				'display_name' => 'View All Configures',
				'created_at' => '2015-04-21 05:16:31',
				'updated_at' => '2015-04-21 05:16:31',
			),
			16 =>
			array (
				'id' => 17,
				'name' => 'configures_view_owner',
				'display_name' => 'View Owner Configures',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			17 =>
			array (
				'id' => 18,
				'name' => 'configures_create_owner',
				'display_name' => 'Create Owner Configures',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			18 =>
			array (
				'id' => 19,
				'name' => 'configures_edit_all',
				'display_name' => 'Edit All Configures',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			19 =>
			array (
				'id' => 20,
				'name' => 'configures_edit_owner',
				'display_name' => 'Edit Owner Configures',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			20 =>
			array (
				'id' => 21,
				'name' => 'configures_delete_all',
				'display_name' => 'Delete All Configures',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			21 =>
			array (
				'id' => 22,
				'name' => 'configures_delete_owner',
				'display_name' => 'Delete Owner Configures',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			22 =>
			array (
				'id' => 23,
				'name' => 'contacts_view_all',
				'display_name' => 'View All Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			23 =>
			array (
				'id' => 24,
				'name' => 'contacts_view_owner',
				'display_name' => 'View Owner Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			24 =>
			array (
				'id' => 25,
				'name' => 'contacts_create_owner',
				'display_name' => 'Create Owner Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			25 =>
			array (
				'id' => 26,
				'name' => 'contacts_edit_all',
				'display_name' => 'Edit All Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			26 =>
			array (
				'id' => 27,
				'name' => 'contacts_edit_owner',
				'display_name' => 'Edit Owner Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			27 =>
			array (
				'id' => 28,
				'name' => 'contacts_delete_all',
				'display_name' => 'Delete All Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			28 =>
			array (
				'id' => 29,
				'name' => 'contacts_delete_owner',
				'display_name' => 'Delete Owner Contacts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			29 =>
			array (
				'id' => 30,
				'name' => 'images_view_all',
				'display_name' => 'View All Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			30 =>
			array (
				'id' => 31,
				'name' => 'images_view_owner',
				'display_name' => 'View Owner Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			31 =>
			array (
				'id' => 32,
				'name' => 'images_create_owner',
				'display_name' => 'Create Owner Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			32 =>
			array (
				'id' => 33,
				'name' => 'images_edit_all',
				'display_name' => 'Edit All Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			33 =>
			array (
				'id' => 34,
				'name' => 'images_edit_owner',
				'display_name' => 'Edit Owner Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			34 =>
			array (
				'id' => 35,
				'name' => 'images_delete_all',
				'display_name' => 'Delete All Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			35 =>
			array (
				'id' => 36,
				'name' => 'images_delete_owner',
				'display_name' => 'Delete Owner Images',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			36 =>
			array (
				'id' => 37,
				'name' => 'layouts_view_all',
				'display_name' => 'View All Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			37 =>
			array (
				'id' => 38,
				'name' => 'layouts_view_owner',
				'display_name' => 'View Owner Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			38 =>
			array (
				'id' => 39,
				'name' => 'layouts_create_owner',
				'display_name' => 'Create Owner Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			39 =>
			array (
				'id' => 40,
				'name' => 'layouts_edit_all',
				'display_name' => 'Edit All Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			40 =>
			array (
				'id' => 41,
				'name' => 'layouts_edit_owner',
				'display_name' => 'Edit Owner Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			41 =>
			array (
				'id' => 42,
				'name' => 'layouts_delete_all',
				'display_name' => 'Delete All Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			42 =>
			array (
				'id' => 43,
				'name' => 'layouts_delete_owner',
				'display_name' => 'Delete Owner Layouts',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			43 =>
			array (
				'id' => 44,
				'name' => 'menus_view_all',
				'display_name' => 'View All Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			44 =>
			array (
				'id' => 45,
				'name' => 'menus_view_owner',
				'display_name' => 'View Owner Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			45 =>
			array (
				'id' => 46,
				'name' => 'menus_create_owner',
				'display_name' => 'Create Owner Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			46 =>
			array (
				'id' => 47,
				'name' => 'menus_edit_all',
				'display_name' => 'Edit All Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			47 =>
			array (
				'id' => 48,
				'name' => 'menus_edit_owner',
				'display_name' => 'Edit Owner Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			48 =>
			array (
				'id' => 49,
				'name' => 'menus_delete_all',
				'display_name' => 'Delete All Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			49 =>
			array (
				'id' => 50,
				'name' => 'menus_delete_owner',
				'display_name' => 'Delete Owner Menus',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			50 =>
			array (
				'id' => 51,
				'name' => 'pages_view_all',
				'display_name' => 'View All Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			51 =>
			array (
				'id' => 52,
				'name' => 'pages_view_owner',
				'display_name' => 'View Owner Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			52 =>
			array (
				'id' => 53,
				'name' => 'pages_create_owner',
				'display_name' => 'Create Owner Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			53 =>
			array (
				'id' => 54,
				'name' => 'pages_edit_all',
				'display_name' => 'Edit All Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			54 =>
			array (
				'id' => 55,
				'name' => 'pages_edit_owner',
				'display_name' => 'Edit Owner Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			55 =>
			array (
				'id' => 56,
				'name' => 'pages_delete_all',
				'display_name' => 'Delete All Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			56 =>
			array (
				'id' => 57,
				'name' => 'pages_delete_owner',
				'display_name' => 'Delete Owner Pages',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			57 =>
			array (
				'id' => 58,
				'name' => 'productcategories_view_all',
				'display_name' => 'View All Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			58 =>
			array (
				'id' => 59,
				'name' => 'productcategories_view_owner',
				'display_name' => 'View Owner Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			59 =>
			array (
				'id' => 60,
				'name' => 'productcategories_create_owner',
				'display_name' => 'Create Owner Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			60 =>
			array (
				'id' => 61,
				'name' => 'productcategories_edit_all',
				'display_name' => 'Edit All Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			61 =>
			array (
				'id' => 62,
				'name' => 'productcategories_edit_owner',
				'display_name' => 'Edit Owner Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			62 =>
			array (
				'id' => 63,
				'name' => 'productcategories_delete_all',
				'display_name' => 'Delete All Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			63 =>
			array (
				'id' => 64,
				'name' => 'productcategories_delete_owner',
				'display_name' => 'Delete Owner Productcategories',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			64 =>
			array (
				'id' => 65,
				'name' => 'productoptiongroups_view_all',
				'display_name' => 'View All Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			65 =>
			array (
				'id' => 66,
				'name' => 'productoptiongroups_view_owner',
				'display_name' => 'View Owner Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			66 =>
			array (
				'id' => 67,
				'name' => 'productoptiongroups_create_owner',
				'display_name' => 'Create Owner Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			67 =>
			array (
				'id' => 68,
				'name' => 'productoptiongroups_edit_all',
				'display_name' => 'Edit All Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			68 =>
			array (
				'id' => 69,
				'name' => 'productoptiongroups_edit_owner',
				'display_name' => 'Edit Owner Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			69 =>
			array (
				'id' => 70,
				'name' => 'productoptiongroups_delete_all',
				'display_name' => 'Delete All Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			70 =>
			array (
				'id' => 71,
				'name' => 'productoptiongroups_delete_owner',
				'display_name' => 'Delete Owner Productoptiongroups',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			71 =>
			array (
				'id' => 72,
				'name' => 'productoptions_view_all',
				'display_name' => 'View All Productoptions',
				'created_at' => '2015-04-21 05:16:32',
				'updated_at' => '2015-04-21 05:16:32',
			),
			72 =>
			array (
				'id' => 73,
				'name' => 'productoptions_view_owner',
				'display_name' => 'View Owner Productoptions',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			73 =>
			array (
				'id' => 74,
				'name' => 'productoptions_create_owner',
				'display_name' => 'Create Owner Productoptions',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			74 =>
			array (
				'id' => 75,
				'name' => 'productoptions_edit_all',
				'display_name' => 'Edit All Productoptions',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			75 =>
			array (
				'id' => 76,
				'name' => 'productoptions_edit_owner',
				'display_name' => 'Edit Owner Productoptions',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			76 =>
			array (
				'id' => 77,
				'name' => 'productoptions_delete_all',
				'display_name' => 'Delete All Productoptions',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			77 =>
			array (
				'id' => 78,
				'name' => 'productoptions_delete_owner',
				'display_name' => 'Delete Owner Productoptions',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			78 =>
			array (
				'id' => 79,
				'name' => 'producttypes_view_all',
				'display_name' => 'View All Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			79 =>
			array (
				'id' => 80,
				'name' => 'producttypes_view_owner',
				'display_name' => 'View Owner Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			80 =>
			array (
				'id' => 81,
				'name' => 'producttypes_create_owner',
				'display_name' => 'Create Owner Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			81 =>
			array (
				'id' => 82,
				'name' => 'producttypes_edit_all',
				'display_name' => 'Edit All Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			82 =>
			array (
				'id' => 83,
				'name' => 'producttypes_edit_owner',
				'display_name' => 'Edit Owner Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			83 =>
			array (
				'id' => 84,
				'name' => 'producttypes_delete_all',
				'display_name' => 'Delete All Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			84 =>
			array (
				'id' => 85,
				'name' => 'producttypes_delete_owner',
				'display_name' => 'Delete Owner Producttypes',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			85 =>
			array (
				'id' => 86,
				'name' => 'products_view_all',
				'display_name' => 'View All Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			86 =>
			array (
				'id' => 87,
				'name' => 'products_view_owner',
				'display_name' => 'View Owner Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			87 =>
			array (
				'id' => 88,
				'name' => 'products_create_owner',
				'display_name' => 'Create Owner Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			88 =>
			array (
				'id' => 89,
				'name' => 'products_edit_all',
				'display_name' => 'Edit All Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			89 =>
			array (
				'id' => 90,
				'name' => 'products_edit_owner',
				'display_name' => 'Edit Owner Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			90 =>
			array (
				'id' => 91,
				'name' => 'products_delete_all',
				'display_name' => 'Delete All Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			91 =>
			array (
				'id' => 92,
				'name' => 'products_delete_owner',
				'display_name' => 'Delete Owner Products',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			92 =>
			array (
				'id' => 93,
				'name' => 'roles_view_all',
				'display_name' => 'View All Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			93 =>
			array (
				'id' => 94,
				'name' => 'roles_view_owner',
				'display_name' => 'View Owner Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			94 =>
			array (
				'id' => 95,
				'name' => 'roles_create_owner',
				'display_name' => 'Create Owner Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			95 =>
			array (
				'id' => 96,
				'name' => 'roles_edit_all',
				'display_name' => 'Edit All Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			96 =>
			array (
				'id' => 97,
				'name' => 'roles_edit_owner',
				'display_name' => 'Edit Owner Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			97 =>
			array (
				'id' => 98,
				'name' => 'roles_delete_all',
				'display_name' => 'Delete All Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			98 =>
			array (
				'id' => 99,
				'name' => 'roles_delete_owner',
				'display_name' => 'Delete Owner Roles',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			99 =>
			array (
				'id' => 100,
				'name' => 'users_view_all',
				'display_name' => 'View All Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			100 =>
			array (
				'id' => 101,
				'name' => 'users_view_owner',
				'display_name' => 'View Owner Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			101 =>
			array (
				'id' => 102,
				'name' => 'users_create_owner',
				'display_name' => 'Create Owner Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			102 =>
			array (
				'id' => 103,
				'name' => 'users_edit_all',
				'display_name' => 'Edit All Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			103 =>
			array (
				'id' => 104,
				'name' => 'users_edit_owner',
				'display_name' => 'Edit Owner Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			104 =>
			array (
				'id' => 105,
				'name' => 'users_delete_all',
				'display_name' => 'Delete All Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			105 =>
			array (
				'id' => 106,
				'name' => 'users_delete_owner',
				'display_name' => 'Delete Owner Users',
				'created_at' => '2015-04-21 05:16:33',
				'updated_at' => '2015-04-21 05:16:33',
			),
			106 =>
			array (
				'id' => 107,
				'name' => 'boxshape_view_all',
				'display_name' => 'View All Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			107 =>
			array (
				'id' => 108,
				'name' => 'boxshape_view_owner',
				'display_name' => 'View Owner Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			108 =>
			array (
				'id' => 109,
				'name' => 'boxshape_create_owner',
				'display_name' => 'Create Owner Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			109 =>
			array (
				'id' => 110,
				'name' => 'boxshape_edit_all',
				'display_name' => 'Edit All Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			110 =>
			array (
				'id' => 111,
				'name' => 'boxshape_edit_owner',
				'display_name' => 'Edit Owner Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			111 =>
			array (
				'id' => 112,
				'name' => 'boxshape_delete_all',
				'display_name' => 'Delete All Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			112 =>
			array (
				'id' => 113,
				'name' => 'boxshape_delete_owner',
				'display_name' => 'Delete Owner Boxshape',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			113 =>
			array (
				'id' => 114,
				'name' => 'dashboards_view_all',
				'display_name' => 'View All Dashboards',
				'created_at' => '2015-04-24 02:16:43',
				'updated_at' => '2015-04-24 02:16:43',
			),
			114 =>
			array (
				'id' => 115,
				'name' => 'dashboards_view_owner',
				'display_name' => 'View Owner Dashboards',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			115 =>
			array (
				'id' => 116,
				'name' => 'dashboards_create_owner',
				'display_name' => 'Create Owner Dashboards',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			116 =>
			array (
				'id' => 117,
				'name' => 'dashboards_edit_all',
				'display_name' => 'Edit All Dashboards',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			117 =>
			array (
				'id' => 118,
				'name' => 'dashboards_edit_owner',
				'display_name' => 'Edit Owner Dashboards',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			118 =>
			array (
				'id' => 119,
				'name' => 'dashboards_delete_all',
				'display_name' => 'Delete All Dashboards',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			119 =>
			array (
				'id' => 120,
				'name' => 'dashboards_delete_owner',
				'display_name' => 'Delete Owner Dashboards',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			120 =>
			array (
				'id' => 121,
				'name' => 'emailtemplates_view_all',
				'display_name' => 'View All Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			121 =>
			array (
				'id' => 122,
				'name' => 'emailtemplates_view_owner',
				'display_name' => 'View Owner Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			122 =>
			array (
				'id' => 123,
				'name' => 'emailtemplates_create_owner',
				'display_name' => 'Create Owner Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			123 =>
			array (
				'id' => 124,
				'name' => 'emailtemplates_edit_all',
				'display_name' => 'Edit All Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			124 =>
			array (
				'id' => 125,
				'name' => 'emailtemplates_edit_owner',
				'display_name' => 'Edit Owner Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			125 =>
			array (
				'id' => 126,
				'name' => 'emailtemplates_delete_all',
				'display_name' => 'Delete All Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			126 =>
			array (
				'id' => 127,
				'name' => 'emailtemplates_delete_owner',
				'display_name' => 'Delete Owner Emailtemplates',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			127 =>
			array (
				'id' => 128,
				'name' => 'orders_view_all',
				'display_name' => 'View All Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			128 =>
			array (
				'id' => 129,
				'name' => 'orders_view_owner',
				'display_name' => 'View Owner Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			129 =>
			array (
				'id' => 130,
				'name' => 'orders_create_owner',
				'display_name' => 'Create Owner Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			130 =>
			array (
				'id' => 131,
				'name' => 'orders_edit_all',
				'display_name' => 'Edit All Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			131 =>
			array (
				'id' => 132,
				'name' => 'orders_edit_owner',
				'display_name' => 'Edit Owner Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			132 =>
			array (
				'id' => 133,
				'name' => 'orders_delete_all',
				'display_name' => 'Delete All Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			133 =>
			array (
				'id' => 134,
				'name' => 'orders_delete_owner',
				'display_name' => 'Delete Owner Orders',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			134 =>
			array (
				'id' => 135,
				'name' => 'shapelayouts_view_all',
				'display_name' => 'View All Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			135 =>
			array (
				'id' => 136,
				'name' => 'shapelayouts_view_owner',
				'display_name' => 'View Owner Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			136 =>
			array (
				'id' => 137,
				'name' => 'shapelayouts_create_owner',
				'display_name' => 'Create Owner Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			137 =>
			array (
				'id' => 138,
				'name' => 'shapelayouts_edit_all',
				'display_name' => 'Edit All Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			138 =>
			array (
				'id' => 139,
				'name' => 'shapelayouts_edit_owner',
				'display_name' => 'Edit Owner Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			139 =>
			array (
				'id' => 140,
				'name' => 'shapelayouts_delete_all',
				'display_name' => 'Delete All Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			140 =>
			array (
				'id' => 141,
				'name' => 'shapelayouts_delete_owner',
				'display_name' => 'Delete Owner Shapelayouts',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			141 =>
			array (
				'id' => 142,
				'name' => 'menusfrontend_view_all',
				'display_name' => 'View All Menusfrontend',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			142 =>
			array (
				'id' => 143,
				'name' => 'menusfrontend_create_all',
				'display_name' => 'Create All Menusfrontend',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			143 =>
			array (
				'id' => 144,
				'name' => 'menusfrontend_edit_all',
				'display_name' => 'Edit All Menusfrontend',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			144 =>
			array (
				'id' => 145,
				'name' => 'menusbackend_view_all',
				'display_name' => 'View All Menusbackend',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			145 =>
			array (
				'id' => 146,
				'name' => 'menusbackend_create_all',
				'display_name' => 'Create All Menusbackend',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
			146 =>
			array (
				'id' => 147,
				'name' => 'menusbackend_edit_all',
				'display_name' => 'Edit All Menusbackend',
				'created_at' => '2015-04-24 02:16:44',
				'updated_at' => '2015-04-24 02:16:44',
			),
		));
	}

}
