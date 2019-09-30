<?php
// UTILITIES

// get category ids by parent

function ws_woo_cats_ids_string( $parent_id = 0 ) {
	$str       = ws_woo_cats_ids( $parent_id );
	$str_clean = str_replace( ' ', '', $str );

	return $str_clean;
}

function ws_woo_cats_ids( $parent_id = 0 ) {
	$parent     = $parent_id;
	$cat_args   = array(
		'parent'      => $parent
	);
	$str = '';

	$product_categories = get_terms( 'product_cat', $cat_args );

	if ( ! empty( $product_categories ) ) {

		foreach ( $product_categories as $key => $category ) {

			$parent_id = $category->term_id;

			$str .=  $parent_id . ',';

			$str .= gc_woo_cats_ids( $parent_id);

		}

	}

	return $str;
}
