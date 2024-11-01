<?php

function ixyt_get_city_page( $country, $city ){
	$response = wp_remote_get( IXYT_URL . 'api/get-city-events?country=' . $country . '&city=' . $city );

	if( ! $response ){
		return null;
	}

	$content = wp_remote_retrieve_body( $response );

    if( ! $content ){
		return null;
	}

	$file = wp_remote_get( sanitize_url( IXYT_URL . '/storage/' . $content ) );

	if( ! $file ){
		return null;
	}

	return json_decode( wp_remote_retrieve_body( $file ) );
}

