<?php

/**
 * Creates the submenu page for the plugin.
 */
class Submenu_Page {


	public function render() {

		echo '<h1 class="cpu-header">Category Wholesale Pricing Utility</h1>';
		echo '<p><strong>STEP 1:</strong> Select the parent category that you want to apply the discounts</p>';

		echo '<form id="get-category-ids" method="post" action="#">';
		$args = array(
			'hide_empty'   => 0,
			'taxonomy'     => 'product_cat',
			'hierarchical' => 1
		);

		wp_dropdown_categories( $args );

		echo '<button type="submit" class="button button-primary">GET CATEGORY IDS</button></form>';

		echo '<img src="' . plugins_url() .'/woocommerce-wholesale-prices-category-utility/images/ajax-loader-horizontal.gif" class="ws-indicator" alt="loading...">';

		echo '<div class="plvr width-50"><form id="update-categories" name="update-categories" method="post" action="#"><div class="form-group"><label for="category_ids">Category IDs</label><textarea class="form-control" id="category_ids"></textarea></div>';

		echo '<p><strong>STEP 2:</strong> Entered discounts must me numeric (62, 57.5) and are percent off the MSRP.</p>';

		$ws_roles = get_option( 'wwp_options_registered_custom_roles' );

		$ws_roles_array = unserialize( $ws_roles );


		$str          = '';
		$ws_role_id   = '';
		$ws_role_name = '';

		foreach ( $ws_roles_array as $key => $innerArray ) {

			$ws_role_id = $key;

			//  Check type
			if ( is_array( $innerArray ) ) {
				//  Scan through inner loop
				foreach ( $innerArray as $key2 => $value ) {
					if ( $key2 == 'roleName' ) {
						$ws_role_name = $value;
					}
				}
			}

			$str .= '<div class="form-group"><label for="' . $ws_role_name . '_wholesale_discount">' . $ws_role_name . '</label> <input type="text" class="form-control discount-amount" id="' . $ws_role_id . '_wholesale_discount" value="50"></div>';
		}

		echo $str;
		echo '<p><strong>IMPORTANT:</strong> Once this form is submitted, just let it run and update the categories. This can take minutes depending on the number of categories.</p>';

		echo '<button type="submit" class="button button-primary">UPDATE CATEGORIES</button></div></form>';



		//echo "<pre>";

		//print_r( $ws_roles_array );

		//echo "</pre>";


	}
}