<?php
/**
 * @wordpress-plugin 
 * Plugin Name: 	rules-price-calculator
 * Plugin URI: 		https://boliviahub.com/rules-price-calculator
 * Description: 	Add a visual sliders to the product, and run a formule to calculate the price. You can use aritmetic operators and 2 tags. If you need improve or add new functionalities/variables/preview/fields, just contactme <a href="mailto:dfortiz@gmail.com">Frank Ortiz</a>
 * Version: 		1.0.0
 * Author: 			Frank Ortiz
 * Author URI: 		https://dfortiz.github.io
 * Text Domain: 	rules-price-calculator
 * Tags: 			woo, woocommerce, price, calculator, quote, quotation, cost.
 * Requires at least: 4.7
 * Donate link: https://dfortiz.github.io
 * Domain Path: / 
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl.html
 * 
 *  PHP version 5.3.0
 *
 * @category    Wordpress_Plugin
 * @package     BH_Plugin
 * @author      Frank Ortiz <dfortiz@gmail.com>
 * @copyright   2021 Boliviahub
 * @license     GNU Public License
 * @version     1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/* Defines plugin's root folder. */
define( 'BH_PLGN_RPC_PATH', plugin_dir_path( __FILE__ ) );
define( 'BH_PLGN_RPC_URL', plugins_url('/', __FILE__ ) );
define( 'BH_PLGN_RPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( "BH_PLGN_RPC_LICENSE", true );

/* General. */
require_once('inc/BH_PLGN_RPC-init.php');

new bhpepc_main();

?>