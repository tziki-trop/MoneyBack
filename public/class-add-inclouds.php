<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       avitrop
 * @since      1.0.0
 *
 * @package    Moneyback
 * @subpackage Moneyback/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Moneyback
 * @subpackage Moneyback/includes
 * @author     tziki trop <avitrop@gmail.com>
 */
class loud_includ {

	public function __construct() {
	
		$this->load_dependencies();
	

	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/simple_html_dom.php';
//class-cardcom.php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cardcom.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/set-accsess.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/global-function.php';
  
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-add-field_prodact.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-add-inclouds.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-moneyback-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cpt.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mengge-elementor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-menege-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-option-page-acf.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/cpt.php';

	}

}
new loud_includ();
