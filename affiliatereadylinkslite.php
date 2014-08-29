<?php
/*
 Plugin Name: Affiliate Ready Links Lite
 Plugin URI: http://readythemes.com
 Description: Quickly search Amazon for products and easily insert affiliate links into your post as either text or image links.
 Version: 1.0.0
Author: ReadyThemes
Author URI:  http://www.readythemes.com/
Tested up to: 4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if(!class_exists('RdyLnk')) {
	class RdyLnk {

		/// CONSTANTS
		const VERSION = '1.0.0';

		//// KEYS
		const SETTINGS_KEY = '_RdyLnk_settings';

		//// SHORTCODES
		const SHORTCODE_IMAGE = 'readylinks-image';
		const SHORTCODE_LINK = 'readylinks-link';

		//// TRANSIENT KEYS
		const TRANSIENT_ASIN_ITEM = '_RdyLnk_by_asin_';
		const TRANSIENT_ASIN_ITEM_TIMEOUT = 259200; // 3 Days

		/// DATA STORAGE
		private static $admin_page_hooks = array('post.php', 'post-new.php');
		private static $default_settings = array();

		public static function init() {
			self::initialize_data();

			self::add_actions();
			self::add_filters();

			self::register_shortcodes();
		}

		private static function add_actions() {
			$settings = self::get_settings();
			foreach(array_keys($settings['post-types']) as $type) {
				add_action("add_meta_boxes_{$type}", array(__CLASS__, 'add_RdyLnk_meta_box'));
			}

			/// NORMAL CALLBACKS
			if(is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_administrative_resources'));
				add_action('admin_menu', array(__CLASS__, 'add_administrative_interface_items'));
			}

			/// AJAX CALLBACKS
			add_action('wp_ajax_RdyLnk', array(__CLASS__, 'ajax_handle_requests'));
		}

		private static function add_filters() {
			add_filter('RdyLnk_pre_settings_save', array(__CLASS__, 'sanitize_settings'));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'add_settings_link'));
		}

		private static function initialize_data() {
			self::$default_settings = array(
				'access-key-id' => '',
				'secret-access-key' => '',

				'default-search-locale' => 'us',
				'default-search-index' => 'All',
				'post-types' => array('post' => 'yes', 'page' => 'yes'),

				'output-css' => 'yes',

				'affiliate-links-new-window' => 'no',
				'affiliate-links-nofollow' => 'no',
				'affiliate-links-cloaking' => 'no',
				'affiliate-links-cloaking-prefix' => 'product',
				'affiliate-locale' => array(),
			);

			$settings = self::get_settings();
			Amazon_API::set_credentials($settings['access-key-id'], $settings['secret-access-key']);
		}

		private static function register_shortcodes() {
			add_shortcode(self::SHORTCODE_IMAGE, array(__CLASS__, 'shortcode_display_image'));
			add_shortcode(self::SHORTCODE_LINK, array(__CLASS__, 'shortcode_display_link'));
		}

		/// AJAX CALLBACKS

		public static function ajax_handle_requests() {
			$data = stripslashes_deep($_REQUEST);

			$settings = self::get_settings();
			if(empty($data['keywords'])) {
				$results = array('error_message' => __('Please provide some keywords to search for products.'));
			} else if(empty($settings['access-key-id']) || empty($settings['secret-access-key'])) {
				$results = array('error_message' => sprintf(__('You have not yet set your Amazon credentials.  Please <a href="%s" target="_blank">configure them</a> now.'), admin_url('options-general.php?page=RdyLnk')));
			} else {
				$index = $data['index'];
				$keywords = $data['keywords'];
				$locale = $data['locale'];
				$page = min((is_numeric($data['page']) ? intval($data['page']) : 1), 10);

				$associate_tag = $settings['affiliate-locale'][$locale];

				$item_search_request = array(
					'associate_tag' => $associate_tag,
					'index' => $index,
					'keywords' => $keywords,
					'locale' => $locale,
					'locale_name' => Amazon_API::get_locale_name($locale),
					'locale_tld' => Amazon_API::get_locale_tld($locale),
				);

				$product_results = array();
				if(preg_match('/^[0-9A-Z]{10}$/', $keywords)) {
					$item_search_response = self::get_listing_data_for_asin($keywords, $locale);
				} else {
					$item_search_response = Amazon_API::item_search($keywords, $index, $page, $associate_tag, $locale);
				}

				if(is_wp_error($item_search_response)) {
					$results = array('error_message' => $item_search_response->get_error_message());
				} else {
					$results = array(
						'PaginationLinks' => self::get_pagination_links($item_search_response),
						'SearchResponse' => $item_search_response,
						'SearchRequest' => $item_search_request
					);
				}
			}

			echo json_encode($results);
			exit;
		}

		/// CALLBACKS

		public static function add_administrative_interface_items() {
			self::$admin_page_hooks[] = $settings = add_options_page(__('Ready Links Settings'), __('Ready Links'), 'manage_options', 'RdyLnk', array(__CLASS__, 'display_settings_page'));

			add_action("load-{$settings}", array(__CLASS__, 'process_settings_save'));
		}

		public static function add_RdyLnk_meta_box($post) {
			add_meta_box('RdyLnk-search', __('Ready Links Amazon Search'), array(__CLASS__, 'display_meta_box'), $post->post_type, 'normal', 'core');
		}

		public static function add_settings_link($actions) {
			$actions = array('settings' => sprintf('<a href="%s" title="%s">%s</a>', admin_url('options-general.php?page=RdyLnk'), __('Configure Ready Links.'), __('Settings'))) + $actions;

			return $actions;
		}

		public static function add_rewrite_rules($wp_rewrite) {
			$settings = self::get_settings();

			if('yes' == $settings['affiliate-links-cloaking']) {
				$prefix = empty($settings['affiliate-links-cloaking-prefix']) ? 'product' : $settings['affiliate-links-cloaking-prefix'];

				$new = array(
					"{$prefix}/([^/]+)/([^/]+)/([^/]+)/?$" => 'index.php?RdyLnk-locale='.$wp_rewrite->preg_index(1).'&RdyLnk-asin='.$wp_rewrite->preg_index(2).'&RdyLnk-code='.$wp_rewrite->preg_index(3)
				);

				$wp_rewrite->rules = $new + $wp_rewrite->rules;
			}
		}

		public static function enqueue_administrative_resources($hook) {
			if(!in_array($hook, self::$admin_page_hooks)) { return; }

			wp_enqueue_style('RdyLnk', plugins_url('resources/backend/RdyLnk.css', __FILE__), array(), self::VERSION);
			wp_enqueue_script('RdyLnk', plugins_url('resources/backend/RdyLnk.js', __FILE__), array('jquery', 'jquery-form'), self::VERSION);

			$configuration = array(
				'image_shortcode' => self::SHORTCODE_IMAGE,
				'link_shortcode' => self::SHORTCODE_LINK
			);

			wp_localize_script('RdyLnk', 'RdyLnk_Configuration', $configuration);
		}

		public static function enqueue_frontend_resources() {
			$settings = self::get_settings();

			if('yes' == $settings['output-css']) {
				wp_enqueue_style('RdyLnk', plugins_url('resources/frontend/RdyLnk.css', __FILE__), array(), self::VERSION);
			}

			if('yes' == $settings['output-js']) {
				$settings = self::get_settings();
				$popup_url = 'http://wms.assoc-amazon.com/20070822/US/js/link-enhancer-common.js';
				if(!empty($settings['affiliate-locale']['us'])) {
					$popup_url = add_query_arg(array('tag' => $settings['affiliate-locale']['us']), $popup_url);
				}

				wp_enqueue_script('amazon-preview', $popup_url, array(), '', true);
			}
		}

		public static function process_settings_save() {
			$data = RdyLnk_trim_r(stripslashes_deep($_POST));

			if(isset($data['save-RdyLnk-settings-nonce']) && wp_verify_nonce($data['save-RdyLnk-settings-nonce'], 'save-RdyLnk-settings')) {
				self::set_settings(apply_filters('RdyLnk_pre_settings_save', $data['RdyLnk']));

				flush_rewrite_rules();

				add_settings_error('general', 'settings_updated', __('Settings saved.'), 'updated');
				set_transient('settings_errors', get_settings_errors(), 30);

				wp_redirect(add_query_arg(array('page' => 'RdyLnk', 'settings-updated' => 'true'), admin_url('options-general.php')));
				exit;
			}
		}

		public static function redirect_to_amazon($wp) {
			$vars = $wp->query_vars;

			if($vars['RdyLnk-locale'] && $vars['RdyLnk-asin'] && $vars['RdyLnk-code']) {
				$asin = $vars['RdyLnk-asin'];
				$associate_tag = $vars['RdyLnk-code'];
				$locale = $vars['RdyLnk-locale'];

				$url = sprintf('http://www.amazon.%s/dp/%s', Amazon_API::get_locale_tld($locale), $asin);
				if(!empty($associate_tag)) {
					$url = add_query_arg(array('tag' => $associate_tag), $url);
				}

				wp_redirect($url);
				exit;
			}
		}

		public static function sanitize_settings($settings) {
			$settings['post-types'] = (array)$settings['post-types'];

			foreach(array('affiliate-links-new-window') as $checkbox_key) {
				$settings[$checkbox_key] = 'yes' == $settings[$checkbox_key] ? 'yes' : 'no';
			}

			return $settings;
		}

		/// SHORTCODE DISPLAY

		public static function shortcode_display_image($atts, $content = null) {
			$atts = shortcode_atts(array('align' => 'none', 'alt' => __('Amazon Image'), 'asin' => 0, 'height' => '', 'keywords' => '', 'locale' => 'us', 'src' => '', 'width' => ''), $atts);

			foreach($atts as $key => $att) {
				$atts[$key] = esc_attr($att);
			}
			extract($atts);

			if((empty($asin) && empty($keywords)) || empty($src)) {
				return '';
			} else {
				$image_attributes = sprintf('src="%s" ', $src);

				if(!empty($align)) {
					$image_attributes .= sprintf('class="align%s" ', $align);
				}

				if(!empty($alt)) {
					$image_attributes .= sprintf('alt="%s" ', $alt);
				}

				if(!empty($height)) {
					$image_attributes .= sprintf('height="%s" ', $height);
				}

				if(!empty($width)) {
					$image_attributes .= sprintf('width="%s" ', $width);
				}

				$url = empty($asin) ? self::get_search_results_link($keywords, $locale) : self::get_link_for_visitor($asin, $locale);

				return sprintf('<a class="RdyLnk-link" %s href="%s"><img %s /></a>', self::get_link_attributes(), $url, $image_attributes);
			}
		}

		public static function shortcode_display_link($atts, $content = null) {
			$atts = shortcode_atts(array('asin' => 0, 'keywords' => '', 'locale' => 'us'), $atts);
			extract($atts);

			$settings = self::get_settings();

			$url = empty($asin) ? self::get_search_results_link($keywords, $locale) : self::get_link_for_visitor($asin, $locale);

			if('yes' == $settings['output-js']) {
				$url = add_query_arg(array('linkCode' => 'as2'), $url);
			}

			return (empty($asin) && empty($keywords)) ? $content : sprintf('<a class="RdyLnk-link" %s href="%s">%s</a>', self::get_link_attributes(), $url, $content);
		}

		/// DISPLAY CALLBACKS

		public static function display_meta_box($post) {
			$placeholder_image = plugins_url('resources/backend/images/75x75.gif', __FILE__);

			$locale_keys = Amazon_API::get_locale_keys();
			$settings = self::get_settings();

			include('views/backend/meta-boxes/search.php');
		}

		public static function display_settings_page() {
			add_meta_box('RdyLnk-settings-api', __('API Credentials'), array(__CLASS__, 'display_settings_meta_box_api'), 'RdyLnk-settings', 'normal');
			add_meta_box('RdyLnk-settings-search-interface', __('Search Interface'), array(__CLASS__, 'display_settings_meta_box_search_interface'), 'RdyLnk-settings', 'normal');
			add_meta_box('RdyLnk-settings-associate-tags', __('Associate Tags'), array(__CLASS__, 'display_settings_meta_box_associate_tags'), 'RdyLnk-settings', 'normal');
			add_meta_box('RdyLnk-settings-affiliate-links', __('Affiliate Links'), array(__CLASS__, 'display_settings_meta_box_affiliate_links'), 'RdyLnk-settings', 'normal');

			$settings = self::get_settings();

			include('views/backend/settings/settings.php');
		}

		//// SETTINGS PAGE META BOXES

		public static function display_settings_meta_box_api($settings) {
			include('views/backend/settings/sections/02-api.php');
		}

		public static function display_settings_meta_box_search_interface($settings) {
			$locale_keys = Amazon_API::get_locale_keys();

			include('views/backend/settings/sections/03-search-interface.php');
		}

		public static function display_settings_meta_box_associate_tags($settings) {
			$locale_keys = Amazon_API::get_locale_keys();

			include('views/backend/settings/sections/04-associate-tags.php');
		}

		public static function display_settings_meta_box_affiliate_links($settings) {
			$has_permalinks = '' != get_option('permalink_structure');

			include('views/backend/settings/sections/05-affiliate-links.php');
		}

		/// SETTINGS

		private static function get_settings() {
			$settings = wp_cache_get(self::SETTINGS_KEY);

			if(!is_array($settings)) {
				$settings = (array)get_option(self::SETTINGS_KEY, array());

				if(is_array($settings['affiliate-locale'])) {
					$affiliate_locales_translation = array('us' => 'com', 'uk' => 'co.uk', 'jp' => 'co.jp');

					foreach($affiliate_locales_translation as $now => $previous) {
						if(empty($settings['affiliate-locale'][$now]) && !empty($settings['affiliate-locale'][$previous])) {
							$settings['affiliate-locale'][$now] = $settings['affiliate-locale'][$previous];
						}
					}
				}

				$settings = wp_parse_args($settings, self::$default_settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + 24 * 60 * 60);
			}

			return $settings;
		}

		private static function set_settings($settings) {
			if(is_array($settings)) {
				update_option(self::SETTINGS_KEY, $settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + 24 * 60 * 60);
			}
		}

		/// ITEM CACHING

		private static function get_listing_data_for_asin($asin, $asin_locale) {
			$transient_key = self::TRANSIENT_ASIN_ITEM . $asin . $locale;
			$item = get_transient($transient_key);

			if(empty($item)) {
				$item = Amazon_API::item_lookup($asin, 'ASIN', self::get_associate_tag($asin_locale), $asin_locale);

				if(!is_wp_error($item)) {
					set_transient($transient_key, $item, self::TRANSIENT_ASIN_ITEM_TIMEOUT);
				}
			}

			return $item;
		}

		/// LOCALIZATION

		private static function get_link_for_visitor($asin, $asin_locale) {
			$associate_tag = self::get_associate_tag($asin_locale);

			$url = sprintf('http://www.amazon.%s/dp/%s', Amazon_API::get_locale_tld($asin_locale), $asin);
			if(empty($associate_tag)) {
				$url = remove_query_arg('tag', $url);
			} else {
				$url = add_query_arg(array('tag' => $associate_tag), $url);
			}

			return $url;
		}

		/// UTILITY

		private static function get_associate_tag($locale) {
			$settings = self::get_settings();

			return $settings['affiliate-locale'][$locale];
		}

		private static function get_link_attributes() {
			$settings = self::get_settings();

			$newwindow = $settings['affiliate-links-new-window'] == 'yes' ? 'target="_blank"' : '';

			return "$newwindow";
		}

		private static function get_pagination_links($response) {
			foreach($response['OperationRequest']['Arguments']['Argument'] as $argument) {
				if('ItemPage' == $argument['@attributes']['Name']) {
					$current_page = $argument['@attributes']['Value'];
					break;
				}
			}

			if(isset($response['Items']['TotalPages'])) {
				$total_pages = $response['Items']['TotalPages'];
			} else {
				$total_pages = 0;
			}

			$args = array(
				'base' => admin_url('#') . '%_%',
				'format' => '%#%',
				'total' => min(10, $total_pages),
				'current' => $current_page,
				'show_all' => false,
				'prev_next' => true,
				'prev_text' => __('&laquo;'),
				'next_text' => __('&raquo;'),
				'end_size' => 1,
				'mid_size' => 2,
				'type' => 'plain',
				'add_args' => array(),
				'add_fragment' => ''
			);

			return paginate_links($args);
		}
	}

	require_once('lib/amazon.php');
	require_once('lib/template-tags.php');

	RdyLnk::init();
}