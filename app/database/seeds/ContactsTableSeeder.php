<?php

class ContactsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('contacts')->delete();

		/*\DB::table('contacts')->insert(array (
			0 =>
			array (
				'id' => 77,
				'contact_name' => 'Chantel K Bowen',
				'contact_phone' => '647-268-69',
				'contact_email' => 'chantel.k.bowen@gmail.com',
				'contact_message' => 'Hi,

Hope you are doing well.  My boyfriend and I are making our own wine and would like to design a wine label.  Is that something your team does? if yes, what is your estimated cost?

Thanks!
Chantel ',
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2014-07-31 05:37:22',
				'updated_at' => '0000-00-00 00:00:00',
				'read' => 1,
			),
			1 =>
			array (
				'id' => 78,
				'contact_name' => 'Shiz',
				'contact_phone' => '',
				'contact_email' => 'shiz.a08@gmail.com',
				'contact_message' => 'How much do you charge for shipping. I live on Mississauga, ON',
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2014-07-31 05:37:22',
				'updated_at' => '0000-00-00 00:00:00',
				'read' => 1,
			),
			2 =>
			array (
				'id' => 79,
				'contact_name' => 'Katy',
				'contact_phone' => '',
				'contact_email' => 'katyoneile@gmail.com',
			'contact_message' => 'Hello,   I just wanted to contact you in regards to promotion, would you need any promoters? At the moment I am working with smaller and larger businesses around the world helping them to increase sales and get them more publicity, etc. I discovered your brand on Instagram and I would be thrilled with the opportunity to collaborate with you and feature/promote your products on all of my social media platforms. My followers are always interested in new styles and businesses. Some of my promotions are shown on my Instagram ( katyoneile_) and more promotions will be featured on my upcoming blog which I\'m in the process of making.  Let me know what you think.  I can\'t wait to hear from you and I hope we have the opportunity to work together in the future.  Thank you',
				'created_by' => 0,
				'updated_by' => 3,
				'created_at' => '2014-07-31 05:37:22',
				'updated_at' => '2015-04-20 07:37:33',
				'read' => 1,
			),
			3 =>
			array (
				'id' => 80,
				'contact_name' => 'Elena Murzello',
				'contact_phone' => '604 418 67',
				'contact_email' => 'ElenaMurzello@gmail.com',
				'contact_message' => 'Hi there Christine!

I was looking to purchase the print "This is the life" but I can\'t seem to find it on your site. Help please!

ELENA:)',
			'created_by' => 0,
			'updated_by' => 0,
			'created_at' => '2014-07-31 05:37:22',
			'updated_at' => '0000-00-00 00:00:00',
			'read' => 1,
		),
		4 =>
		array (
			'id' => 82,
			'contact_name' => 'Melissa Chai',
			'contact_phone' => '302-423-00',
			'contact_email' => 'chaimr@usc.edu',
			'contact_message' => 'I want a 8 x 10 graphic print of the lyrics, "The only risk is that you\'ll go insane" with a black background and white typography. Is there someway I can sample the fonts available? Thank you!',
			'created_by' => 0,
			'updated_by' => 3,
			'created_at' => '2014-07-31 05:37:22',
			'updated_at' => '2015-04-20 07:32:35',
			'read' => 1,
		),
		5 =>
		array (
			'id' => 83,
			'contact_name' => 'James Acan',
			'contact_phone' => '619-793-93',
			'contact_email' => 'james.acan@gmail.com',
		'contact_message' => 'I just placed an order (07/30/14 at 19:44 PDT) for 6 different prints. I want to see if the order actually went through as I received an error message during processing. However, I received a confirmation email from PayPal of the charge. If you can please confirm this with a receipt and/or tracking number, that would be great. Thank you.',
			'created_by' => 0,
			'updated_by' => 3,
			'created_at' => '2014-07-31 05:37:22',
			'updated_at' => '2015-04-20 07:37:30',
			'read' => 1,
		),
		6 =>
		array (
			'id' => 86,
			'contact_name' => 'Penny',
			'contact_phone' => '7788695003',
			'contact_email' => 'hello@purlmama.com',
			'contact_message' => 'Hi Dolch Designs,

We\'ve just discovered your amazing prints and would love to learn more about your wholesale terms. We are an online store, based on Vancouver Island, featuring independent artists and designers.

Kind regards,
Penny
www.purlmama.com',
			'created_by' => 0,
			'updated_by' => 0,
			'created_at' => '2014-08-01 02:42:16',
			'updated_at' => '0000-00-00 00:00:00',
			'read' => 1,
		),
		7 =>
		array (
			'id' => 87,
			'contact_name' => 'Therese nemeth',
			'contact_phone' => '306-737-30',
			'contact_email' => 'therese.nemeth@live.ca',
			'contact_message' => 'Hi
I\'m looking for wall hangings, specific design. With sketches and phrases.  The first or have a sketch of a loaf of bread with the something like the following "Bread... that this house  may never know hunger"
The 2nd, a shaker of salt with the following " Salt... that life may always have flavour" and the third a bottle of wine with " Wine...that joy and prosperity may reign forever". Likely around 10 x  14 or smaller. Not color necessarily ',
			'created_by' => 0,
			'updated_by' => 0,
			'created_at' => '2014-08-14 02:20:50',
			'updated_at' => '0000-00-00 00:00:00',
			'read' => 1,
		),
		8 =>
		array (
			'id' => 88,
		'contact_name' => 'Molly Lacy (GeorgiaLu)',
			'contact_phone' => '303.513.77',
			'contact_email' => 'georgialugifts@gmail.com',
		'contact_message' => 'I am a fan (and follower) on Instagram and would love to offer your unique designs to our inventory at GeorgiaLu, a personalized gift and stationery shop in Denver.  We officially "launched" last week and I just know your cards,  prints, etc. would fly off our shelves!

Please let me know if there is any more information that you may need from me.  I look forward to hearing from you soon.  Thanks so much!

- Molly.',
			'created_by' => 0,
			'updated_by' => 0,
			'created_at' => '2014-08-14 15:12:53',
			'updated_at' => '0000-00-00 00:00:00',
			'read' => 1,
		),
	));*/
	}

}
