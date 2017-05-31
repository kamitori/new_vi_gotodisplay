<?php

class PagesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('pages')->delete();
        
		\DB::table('pages')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Terms',
				'short_name' => 'terms',
				'content' => '&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;TERMS &amp;amp; CONDITIONS&lt;/span&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br&gt;&lt;/span&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;Designs&lt;br&gt;&lt;br&gt;&lt;br&gt;&lt;/span&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;All graphics displayed on the Dolch Designs Website are also strictly copy righted. Please respect our work and have the courtesy not to to copy/steal it in any form.&lt;br&gt;&lt;/span&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;Privacy Policy&lt;br&gt;&lt;br&gt;&lt;/span&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;Dolch Designs respects our customers&#039; account information as private and confidential information and will never share this information with any outside afflictions or individuals.&amp;nbsp;&lt;br&gt;&lt;/span&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br&gt;&lt;br&gt;&lt;/span&gt;&lt;br style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;span style=&quot;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;br&gt;&lt;br&gt;&lt;/span&gt;',
				'meta_title' => '',
				'meta_description' => '',
				'type' => 'Default',
				'menu_id' => 48,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 1,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-04-21 07:41:48',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Wholesale',
				'short_name' => 'wholesale',
				'content' => '&lt;div class=&quot;rte-content colored-links&quot; style=&quot;box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, &#039;Helvetica Neue Light&#039;, &#039;Helvetica Neue&#039;, Helvetica, Arial, &#039;Lucida Grande&#039;, sans-serif;font-size:11px;letter-spacing:1px;line-height:17.600000381469727px;&quot;&gt;&lt;p style=&quot;box-sizing:border-box;color:#000000;margin-bottom:20px;&quot;&gt;Dolch Designs is currently looking for retailers to carry our products and would love to do business with you.&amp;nbsp;&lt;/p&gt;&lt;p style=&quot;box-sizing:border-box;color:#000000;margin-bottom:20px;&quot;&gt;For all wholesale and retail inquiries, please fill out our contact form for more information or email us at&amp;nbsp;&lt;strong&gt;wholesale@dolchdesigns.com&lt;/strong&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;box-sizing:border-box;color:#000000;margin-bottom:20px;&quot;&gt;&lt;strong&gt;&lt;br&gt;&lt;/strong&gt;.&lt;/p&gt;&lt;/div&gt;',
				'meta_title' => '',
				'meta_description' => '',
				'type' => 'Default',
				'menu_id' => 42,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 1,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-04-21 07:34:40',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Shipping Information',
				'short_name' => 'shipping-information',
				'content' => '&lt;p&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;Once an order is placed, 1-3 business days is required to package and ship the order. All orders are shipped via Canada Post. Currently we only offer standard shipping, but if you would like to request express shipping please inform us via our contact tab prior to placing an order. All delivery times are an estimate.&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;text-decoration:underline;font-family:Arial, Helvetica, sans-serif;&quot;&gt;&lt;strong&gt;Estimated Delivery Times:&lt;/strong&gt;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;text-decoration:underline;&quot;&gt;Canada: Flat Rate $4.95&lt;/span&gt;&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;Postcards and Paper prints* : 2-5 business days&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;All other products : 3-10 business days&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;&lt;span style=&quot;text-decoration:underline;&quot;&gt;United States:&amp;nbsp;&lt;/span&gt;&lt;/span&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;text-decoration:underline;&quot;&gt;Flat Rate $6.95&lt;/span&gt;&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;Postcards and Paper prints* : 4-6 business days&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;All other products&lt;/span&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt; : 6-12 business days&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;text-decoration:underline;&quot;&gt;International:&amp;nbsp;&lt;/span&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;text-decoration:underline;&quot;&gt;Flat Rate $9.95&lt;/span&gt;&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;Postcards and Paper prints* : 5-8 business days&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;All other products&lt;/span&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;&amp;nbsp;: 6-15 business days&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-family:Arial, Helvetica, sans-serif;&quot;&gt;*Please note that this is an estimate for prints in sizes 8x10 or smaller.&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;',
				'meta_title' => '',
				'meta_description' => '',
				'type' => 'Default',
				'menu_id' => 47,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 1,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-04-21 07:41:45',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'About / Visual Impact',
				'short_name' => 'about-visual-impact',
				'content' => '&lt;p&gt;&lt;span style=&quot;font-size:small;font-family:Arial, Helvetica, sans-serif;&quot;&gt;Visual Impact is a division of Anvy Digital that caters to a consumer based market for wall decor products. Designed for the DIY enthusiast, Visual Impact offers you the ability to create beautiful wall collages. From standardized prints to custom options and layours, our collection of decor items and display designs will satisfy and deflight even the most artistic Do-It-Yourselfer.&amp;nbsp;&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size:small;font-family:Arial, Helvetica, sans-serif;&quot;&gt;Our innovative products have been designed to last and impress with unique features. Our proven and tried products have been added to the collection for a variety of display options for the office or for the home.&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;span style=&quot;font-size:small;font-family:Arial, Helvetica, sans-serif;&quot;&gt;All products from the website are produced and supplied from our production facility in Calgary Alberta.&lt;/span&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;',
				'meta_title' => '',
				'meta_description' => '',
				'type' => 'Default',
				'menu_id' => 40,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 1,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-04-21 07:34:36',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'Contact',
				'short_name' => 'contact',
				'content' => '&lt;div class=&quot;columns large-4&quot;&gt;
&lt;div class=&quot;page-content rte-content colored-links&quot;&gt;
&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;http://vi.anvyonline.com/assets/images/logos/VisualImpact-logo.png&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;General Information : info@visual-impactdesigns.com&lt;/p&gt;&lt;p&gt;Orders : customerservice@visual-impactdesigns.com&lt;/p&gt;&lt;p&gt;Wholesale Inquiries : wholesale@visual-impactdesigns.com&lt;/p&gt;&lt;p&gt;&lt;/p&gt;&lt;p&gt;To request a custom order, please fill out the form with your contact information. Let us know what you&#039;d like to get customized and we will get back to you. &amp;nbsp;&lt;/p&gt;
&lt;/div&gt;
&lt;/div&gt;
&lt;div class=&quot;columns large-7 push-1  &quot;&gt;
&lt;form accept-charset=&quot;UTF-8&quot; class=&quot;contact-form&quot; method=&quot;post&quot;&gt;
&lt;p&gt;
&lt;label&gt;Your Name:&lt;/label&gt;
&lt;input type=&quot;text&quot; id=&quot;contact_name&quot; name=&quot;contact_name&quot; placeholder=&quot;Your name&quot; class=&quot;styled-input&quot; value=&quot;&quot;&gt;
&lt;/p&gt;
&lt;p&gt;
&lt;label&gt;Email:&lt;/label&gt;
&lt;input required=&quot;required&quot; type=&quot;email&quot; id=&quot;contact_email&quot; name=&quot;contact_email&quot; placeholder=&quot;your@email.com&quot; class=&quot;styled-input&quot; value=&quot;&quot;&gt;
&lt;/p&gt;
&lt;p&gt;
&lt;label&gt;Phone Number:&lt;/label&gt;
&lt;input type=&quot;tel&quot; id=&quot;contact_phone&quot; name=&quot;contact_phone&quot; placeholder=&quot;555-555-1234&quot; class=&quot;styled-input&quot; value=&quot;&quot;&gt;
&lt;/p&gt;
&lt;p&gt;
&lt;label&gt;Message:&lt;/label&gt;
&lt;textarea required=&quot;required&quot; rows=&quot;10&quot; cols=&quot;60&quot; id=&quot;contact_message&quot; name=&quot;contact_message&quot; placeholder=&quot;Your Message&quot; class=&quot;styled-input&quot;&gt;&lt;/textarea&gt;
&lt;/p&gt;
&lt;p&gt;
&lt;input class=&quot;button styled-submit&quot; type=&quot;submit&quot; value=&quot;submit&quot; id=&quot;submit&quot;&gt;
&lt;/p&gt;
&lt;/form&gt;
&lt;/div&gt;',
				'meta_title' => NULL,
				'meta_description' => NULL,
				'type' => 'Contact',
				'menu_id' => 39,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 1,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-04-21 07:34:34',
			),
		));
	}

}
