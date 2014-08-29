<?php

class Amazon_API {
	
	private static $locale_associate_signup_url = array('us' => 'https://affiliate-program.amazon.com/', 'ca' => 'https://associates.amazon.ca/', 'cn' => 'https://associates.amazon.cn/', 'de' => 'https://partnernet.amazon.de/', 'es' => 'https://afiliados.amazon.es/', 'fr' => 'https://partenaires.amazon.fr/', 'it' => 'https://programma-affiliazione.amazon.it/', 'jp' => 'https://affiliate.amazon.co.jp/', 'uk' => 'https://affiliate-program.amazon.co.uk/');
	private static $locale_associate_tags = array('ca' => 'al25-20', 'cn' => 'al33-23', 'de' => 'al28-21', 'es' => 'al32-21', 'fr' => 'al30-21', 'it' => 'al31-21', 'jp' => 'al32-22', 'uk' => 'al29-21', 'us' => 'al24-20');
	private static $locale_endpoints = array('ca' => 'http://ecs.amazonaws.ca/onca/xml', 'cn' => 'http://webservices.amazon.cn/onca/xml', 'de' => 'http://ecs.amazonaws.de/onca/xml', 'es' => 'http://webservices.amazon.es/onca/xml', 'fr' => 'http://ecs.amazonaws.fr/onca/xml', 'it' => 'http://webservices.amazon.it/onca/xml', 'jp' => 'http://ecs.amazonaws.jp/onca/xml', 'uk' => 'http://ecs.amazonaws.co.uk/onca/xml', 'us' => 'http://webservices.amazon.com/onca/xml',);
	private static $locale_keys = array('us', 'ca', 'cn', 'de', 'es', 'fr', 'it', 'jp', 'uk');
	private static $locale_names = array('ca' => 'Canada', 'cn' => 'China', 'de' => 'Germany', 'es' => 'Spain', 'fr' => 'France', 'it' => 'Italy', 'jp' => 'Japan', 'uk' => 'United Kingdom', 'us' => 'United States');
	private static $locale_search_indices = array(
		'us' => array('All', 'Apparel', 'Appliances', 'ArtsAndCrafts', 'Automotive', 'Baby', 'Beauty', 'Blended', 'Books', 'Classical', 'DigitalMusic', 'DVD', 'Electronics', 'GourmetFood', 'Grocery', 'HealthPersonalCare', 'HomeGarden', 'Industrial', 'Jewelry', 'KindleStore', 'Kitchen', 'Magazines', 'Marketplace', 'Miscellaneous', 'MobileApps', 'MP3Downloads', 'Music', 'MusicalInstruments', 'MusicTracks', 'OfficeProducts', 'OutdoorLiving', 'PCHardware', 'PetSupplies', 'Photo', 'Shoes', 'Software', 'SportingGoods', 'Tools', 'Toys', 'UnboxVideo', 'VHS', 'Video', 'VideoGames', 'Watches', 'Wireless', 'WirelessAccessories',),
		'uk' => array('All', 'Apparel', 'Automotive', 'Baby', 'Beauty', 'Blended', 'Books', 'Classical', 'DVD', 'Electronics', 'Grocery', 'HealthPersonalCare', 'HomeGarden', 'HomeImprovement', 'Jewelry', 'KindleStore', 'Kitchen', 'Lighting', 'Marketplace', 'MP3Downloads', 'Music', 'MusicalInstruments', 'MusicTracks', 'OfficeProducts', 'OutdoorLiving', 'Outlet', 'PCHardware', 'Shoes', 'Software', 'SoftwareVideoGames', 'SportingGoods', 'Tools', 'Toys', 'VHS', 'Video', 'VideoGames', 'Watches',),
		'de' => array('All', 'Apparel', 'Automotive', 'Baby', 'Beauty', 'Blended', 'Books', 'Classical', 'DVD', 'Electronics', 'ForeignBooks', 'Grocery', 'HealthPersonalCare', 'HomeGarden', 'HomeImprovement', 'Jewelry', 'Kitchen', 'Lighting', 'Magazines', 'Marketplace', 'MP3Downloads', 'Music', 'MusicalInstruments', 'MusicTracks', 'OfficeProducts', 'OutdoorLiving', 'Outlet', 'PCHardware', 'Photo', 'Shoes', 'Software', 'SoftwareVideoGames', 'SportingGoods', 'Tools', 'Toys', 'VHS', 'Video', 'VideoGames', 'Watches',),
		'jp' => array('All', 'Apparel', 'Automotive', 'Baby', 'Beauty', 'Blended', 'Books', 'Classical', 'DVD', 'Electronics', 'ForeignBooks', 'Grocery', 'HealthPersonalCare', 'Hobbies', 'HomeImprovement', 'Jewelry', 'Kitchen', 'Marketplace', 'MP3Downloads', 'Music', 'MusicalInstruments', 'MusicTracks', 'OfficeProducts', 'Shoes', 'Software', 'SportingGoods', 'Toys', 'VHS', 'Video', 'VideoGames',),
		'fr' => array('All', 'Apparel', 'Baby', 'Beauty', 'Blended', 'Books', 'Classical', 'DVD', 'Electronics', 'ForeignBooks', 'HealthPersonalCare', 'Jewelry', 'Kitchen', 'Lighting', 'MP3Downloads', 'Music', 'MusicalInstruments', 'MusicTracks', 'OfficeProducts', 'PCHardware', 'Shoes', 'Software', 'SoftwareVideoGames', 'SportingGoods', 'Toys', 'VHS', 'Video', 'VideoGames', 'Watches',),
		'ca' => array('All', 'Blended', 'Books', 'Classical', 'DVD', 'Electronics', 'ForeignBooks', 'Music', 'Software', 'SoftwareVideoGames', 'VHS', 'Video', 'VideoGames',),
		'it' => array('All', 'Books', 'DVD', 'Electronics', 'ForeignBooks', 'Garden', 'Kitchen', 'Music', 'Shoes', 'Software', 'Toys', 'VideoGames', 'Watches',),
		'cn' => array('All', 'Apparel', 'Appliances', 'Automotive', 'Baby', 'Beauty', 'Books', 'Electronics', 'Grocery', 'HealthPersonalCare', 'HomeGarden', 'HomeImprovement', 'Jewelry', 'Miscellaneous', 'Music', 'OfficeProducts', 'Photo', 'Shoes', 'Software', 'SportingGoods', 'Toys', 'Video', 'VideoGames', 'Watches',),
		'es' => array('All', 'Books', 'DVD', 'Electronics', 'ForeignBooks', 'Kitchen', 'Music', 'Software', 'Toys', 'VideoGames', 'Watches',),
	);
	private static $locale_tlds = array('ca' => 'ca', 'cn' => 'cn', 'de' => 'de', 'es' => 'es', 'fr' => 'fr', 'it' => 'it', 'jp' => 'co.jp', 'uk' => 'co.uk', 'us' => 'com');
	
	private static $item_lookup_identifier_types = array('SKU', 'UPC', 'EAN', 'ISBN', 'ASIN');
	
	private static $search_index_nice_names = array('ArtsAndCrafts' => 'Arts & Crafts', 'DigitalMusic' => 'Digital Music', 'ForeignBooks' => 'Foreign Books', 'GourmetFood' => 'Gourmet Food', 'HealthPersonalCare' => 'Health & Personal Care', 'HomeGarden' => 'Home & Garden', 'HomeImprovement' => 'Home Improvement', 'KindleStore' => 'Kindle Store', 'MP3Downloads' => 'MP3 Downloads', 'MobileApps' => 'Mobile Apps', 'MusicTracks' => 'Music Tracks', 'MusicalInstruments' => 'Musical Instruments', 'OfficeProducts' => 'Office Products', 'OutdoorLiving' => 'Outdoor Living', 'PCHardware' => 'PC Hardware', 'PetSupplies' => 'Pet Supplies', 'SoftwareVideoGames' => 'Software & Video Games', 'SportingGoods' => 'Sporting Goods', 'UnboxVideo' => 'Unbox Video', 'VideoGames' => 'Video Games', 'WirelessAccessories' => 'Wireless Accessories',);
	
	private static $access_key_id = '';
	private static $secret_access_key = '';
	
	// UTILITY
	
	/// ALL LOCALES
	
	public static function get_locale_keys() {
		return self::$locale_keys;
	}
	
	/// SINGLE LOCALE
	
	public static function get_locale_associate_signup_url($locale) {
		return self::$locale_associate_signup_url[$locale];
	}
	
	public static function get_locale_associate_tag($locale) {
		return self::$locale_associate_tags[$locale];
	}
	
	public static function get_locale_endpoint($locale) {
		return self::$locale_endpoints[$locale];
	}

	public static function get_locale_name($locale) {
		return self::$locale_names[$locale];
	}
	
	public static function get_locale_search_indices($locale) {
		return self::$locale_search_indices[$locale];
	}
	
	public static function get_locale_tld($locale) {
		return self::$locale_tlds[$locale];
	}
	
	/// SEARCH INDICES
	
	public static function get_search_index_nice_name($search_index) {
		return isset(self::$search_index_nice_names[$search_index]) ? self::$search_index_nice_names[$search_index] : $search_index;
	}

	// DATA CORRECTNESS
	
	private static function verify_item_lookup_identifier_type($identifier_type) {
		return in_array($identifier_type, self::$item_lookup_identifier_types) ? $identifier_type : false;
	}

	private static function verify_locale($locale) {
		return in_array($locale, self::$locale_keys) ? $locale : false;
	}
	
	private static function verify_search_index($locale, $search_index) {
		return in_array($search_index, self::$locale_search_indices[$locale]) ? $search_index : false;
	}
	
	/// API OPERATIONS
	
	public static function item_lookup($identifier, $identifier_type, $associate_tag, $locale) {
		if(false === self::verify_locale($locale)) {
			return new WP_Error('item_lookup_invalid_locale', __('The locale you provided is invalid.'));
		} else if(false === self::verify_item_lookup_identifier_type($identifier_type)) {
			return new WP_Error('item_lookup_invalid_identifier_type', __('The identifier type you provided is invalid.'));
		}
		
		$query_parameters = array(
			'AssociateTag' => $associate_tag,
			'IdType' => urlencode($identifier_type),
			'ItemId' => urlencode($identifier),
			'Operation' => 'ItemLookup',
			'ResponseGroup' => 'Images,ItemAttributes,Offers,Reviews',
			'Sort' => 'relevancerank',
		);
		
		if('ASIN' != $identifier_type) {
			$query_parameters['SearchIndex'] = 'All';
		}
		
		return self::make_request($query_parameters, $locale);
	}
	
	public static function item_search($keywords, $search_index, $item_page, $associate_tag, $locale) {
		if($item_page < 1 || $item_page > 10) {
			return new WP_Error('item_search_invalid_page', __('The page parameter must be between 1 and 10 for the item search operation.'));
		} else if(false === self::verify_locale($locale)) {
			return new WP_Error('item_search_invalid_locale', __('The locale you provided is invalid.'));
		} else if(false === self::verify_search_index($locale, $search_index)) {
			return new WP_Error('item_search_invalid_search_index', __('The search index you provided is invalid for the specified locale.'));
		}
		
		$query_parameters = array(
			'AssociateTag' => $associate_tag,
			'ItemPage' => $item_page,
			'Keywords' => urlencode($keywords),
			'Operation' => 'ItemSearch',
			'ResponseGroup' => 'Images,ItemAttributes,Offers',
			'SearchIndex' => $search_index,
		);
		
		return self::make_request($query_parameters, $locale);
	}
	
	/// REQUESTS
	
	private static function make_request($query_parameters, $locale) {
		if(empty($query_parameters['AssociateTag'])) {
			$query_parameters['AssociateTag'] = self::$locale_associate_tags[$locale];
		}
			
		if(!isset($query_parameters['AWSAccessKeyId'])) {
			$query_parameters['AWSAccessKeyId'] = self::$access_key_id;
		}
		
		if(!isset($query_parameters['Service'])) {
			$query_parameters['Service'] = 'AWSECommerceService';
		}

		if(!isset($query_parameters['Timestamp'])) {
			$query_parameters['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
		}
		
		if(!isset($query_parameters['Version'])) {
			$query_parameters['Version'] = '2011-08-01';
		}

		$query_url = self::sign_request(add_query_arg($query_parameters, self::$locale_endpoints[$locale]));
		$response = wp_remote_get($query_url, array('timeout' => 10, 'user-agent' => __('RdyLnk for WordPress V' . RdyLnk::VERSION)));
		
		return is_wp_error($response) ? $response : self::parse_response(wp_remote_retrieve_body($response));
	}
	
	
	private static function sign_request($url) {
		$original = $url;

		// Decode anything already encoded
		$url = urldecode($url);

		// Parse the URL into $urlparts
		$urlparts = parse_url($url);

		// Build $params with each name/value pair
		foreach (explode('&', $urlparts['query']) as $part) {
			if (strpos($part, '=')) {
				list($name, $value) = explode('=', $part);
			} else {
				$name = $part;
				$value = '';
			}
			$params[$name] = $value;
		}

		// Sort the array by key
		ksort($params);

		// Build the canonical query string
		$canonical = '';
		foreach ($params as $key=>$val) {
			$canonical .= "{$key}=".rawurlencode($val).'&';
		}
		// Remove the trailing ampersand
		$canonical = preg_replace("/&$/", '', $canonical);

		// Some common replacements and ones that Amazon specifically mentions
		$canonical = str_replace(array(' ', '+', ', ', ';'), array('%20', '%20', urlencode(','), urlencode(':')), $canonical);

		// Build the si
		$string_to_sign = "GET\n{$urlparts['host']}\n{$urlparts['path']}\n$canonical";

		// Calculate our actual signature and base64 encode it
		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, self::$secret_access_key, true));

		// Finally re-build the URL with the proper string and include the Signature
		return "{$urlparts['scheme']}://{$urlparts['host']}{$urlparts['path']}?$canonical&Signature=".rawurlencode($signature);
	}

	/// RESPONSES
	
	private static function parse_response($response_string) {
		$xml = @simplexml_load_string($response_string);
		
		if(!is_object($xml)) {
			$response = new WP_Error('parse_response_xml_error', __('Could not parse the response from Amazon as XML.'));
		} else if(isset($xml->Error)) {
			$response = new WP_Error((string)$xml->Error->Code, (string)$xml->Error->Message);
		} else if(isset($xml->Items->Request->Errors->Error)) {
			$response = new WP_Error((string)$xml->Items->Request->Errors->Error->Code, (string)$xml->Items->Request->Errors->Error->Message);
		} else {
			$response = json_decode(json_encode($xml), true);
			
			if(isset($response['Items']['Item']) && isset($response['Items']['Item']['ASIN'])) {
				$response['Items']['Item'] = array($response['Items']['Item']);
			}
			
			foreach($response['Items']['Item'] as $item_key => $item) {
				if(!is_array($item['ImageSets']['ImageSet'])) {
					$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] = array();
				}
				
				if(isset($response['Items']['Item'][$item_key]['ImageSets']['ImageSet'][0])) {
					$response['Items']['Item'][$item_key]['ImageSets']['ImageSet'] = $response['Items']['Item'][$item_key]['ImageSets']['ImageSet'][0];
				}
			}
			
		}
		
		return $response;
	}
	
	/// UTILITY
	
	public static function set_credentials($access_key_id, $secret_access_key) {
		self::$access_key_id = $access_key_id;
		self::$secret_access_key = $secret_access_key;
	}
}
