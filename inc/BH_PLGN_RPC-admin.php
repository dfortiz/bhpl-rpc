<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	class bhpepc_admin { // rules-price-calculator
		private $opt=null;
	
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'rules_price_calculator__add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'rules_price_calculator__page_init' ) );
		}
	
		public function rules_price_calculator__add_plugin_page() {
			add_menu_page(
				'rules Price Calculator', // page_title
				'RPC!', // menu_title
				'manage_options', // capability
				'rules-price-calculator', // menu_slug
				array( $this, 'rules_price_calculator__create_admin_page' ), // function
				BH_PLGN_RPC_URL . 'assets/img/bhic-rpc-icon.png'
				//2 // position
			);
		}
		
	
		public function rules_price_calculator__create_admin_page() {
			$this->opt = get_option( 'rules_price_calculator__option_name' ); ?>
	
			<div class="wrap">
				<h2>RULES PRICE CALCULATOR</h2>
				<p>This plugins add two sliders to easily calculate the products price using a simple logic: <br/>
				The price_base is the price for the first square unit, and the remaining units will use the values by ranges <br/>
				If the remaining units is between 1 and 20, then the total price is price_base + the remaining units * range1 <br/>
				If the remaining units is between 21 and 40, then the total price is price_base + the remaining units * range2 <br/>
				and the same logic for the range3, range4 and range5 <br/>
				If you have any question or need some improvement please contact me <a href="mailto:dfortiz@gmail.com" target="_blank">Frank Ortiz: dfortiz@gmail.com</a></p>
				<?php settings_errors(); ?>
				<form method="post" action="options.php">
					<?php
						settings_fields( 'rules_price_calculator__option_group' );
						do_settings_sections( 'rules-price-calculator-admin' );
						submit_button();
					?>
				</form>
			</div>
		<?php }



	
		public function rules_price_calculator__page_init() {
			register_setting(
				'rules_price_calculator__option_group', // option_group
				'rules_price_calculator__option_name', // option_name
				array( $this, 'rules_price_calculator__sanitize' ) // sanitize_callback
			);

			add_settings_section(
				'rules_price_calculator__setting_section', // id
				'Settings', // title
				array( $this, 'rules_price_calculator__section_info' ), // callback
				'rules-price-calculator-admin' // page
			);
			// $this->add_settings_section();

			add_settings_field(
				'productid', // id
				'product-id', // title
				array( $this, 'productid_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'formule_base', // id
				'price_base', // title
				array( $this, 'formule_base_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'formule_range1', // id
				'range1', // title
				array( $this, 'formule_range1_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'formule_range2', // id
				'range2', // title
				array( $this, 'formule_range2_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);
			add_settings_field(
				'formule_range3', // id
				'range3', // title
				array( $this, 'formule_range3_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);
			add_settings_field(
				'formule_range4', // id
				'range4', // title
				array( $this, 'formule_range4_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);
			add_settings_field(
				'formule_range5', // id
				'range5', // title
				array( $this, 'formule_range5_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'labelvalue1', // id
				'slider-1', // title
				array( $this, 'labelvalue1_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'labelvalue2', // id
				'slider-2', // title
				array( $this, 'labelvalue2_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'labelresult', // id
				'result-calculate', // title
				array( $this, 'labelresult_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'currencysymbol', // id
				'currency-symbol', // title
				array( $this, 'currencysymbol_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);

			add_settings_field(
				'active', // id
				'enable', // title
				array( $this, 'active_callback' ), // callback
				'rules-price-calculator-admin', // page
				'rules_price_calculator__setting_section' // section
			);
		}
	
		public function rules_price_calculator__sanitize($input) {
			$sanitary_values = array();
			if ( isset( $input['productid'] ) ) {
				$sanitary_values['productid'] = sanitize_text_field( $input['productid'] );
			}
			if ( isset( $input['formule'] ) ) {
				$sanitary_values['formule'] = sanitize_text_field( $input['formule'] );
			}
			if ( isset( $input['formule_base'] ) ) {
				$sanitary_values['formule_base'] = sanitize_text_field( $input['formule_base'] );
			}
			if ( isset( $input['formule_range1'] ) ) {
				$sanitary_values['formule_range1'] = sanitize_text_field( $input['formule_range1'] );
			}
			if ( isset( $input['formule_range2'] ) ) {
				$sanitary_values['formule_range2'] = sanitize_text_field( $input['formule_range2'] );
			}
			if ( isset( $input['formule_range3'] ) ) {
				$sanitary_values['formule_range3'] = sanitize_text_field( $input['formule_range3'] );
			}
			if ( isset( $input['formule_range4'] ) ) {
				$sanitary_values['formule_range4'] = sanitize_text_field( $input['formule_range4'] );
			}
			if ( isset( $input['formule_range5'] ) ) {
				$sanitary_values['formule_range5'] = sanitize_text_field( $input['formule_range5'] );
			}
			if ( isset( $input['labelvalue1'] ) ) {
				$sanitary_values['labelvalue1'] = sanitize_text_field( $input['labelvalue1'] );
			}
			if ( isset( $input['labelvalue2'] ) ) {
				$sanitary_values['labelvalue2'] = sanitize_text_field( $input['labelvalue2'] );
			}
			if ( isset( $input['labelresult'] ) ) {
				$sanitary_values['labelresult'] = sanitize_text_field( $input['labelresult'] );
			}
			if ( isset( $input['currencysymbol'] ) ) {
				$sanitary_values['currencysymbol'] = sanitize_text_field( $input['currencysymbol'] );
			}
			if ( isset( $input['active'] ) ) {
				$sanitary_values['active'] = $input['active'];
			}
			return $sanitary_values;
		}

		public function rules_price_calculator__section_info() {
		}
	
		public function productid_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[productid]" id="productid" value="%s">
				<br/><span>You can enter the productid
				<br/> Check de ID attribute on the product list
				</span>',
				isset( $this->opt['productid'] ) ? esc_attr( $this->opt['productid']) : ''
			);
		}	

		public function formule_base_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[formule_base]" id="formule_base" value="%s">
				<br/><span>Price for the first sq units:
				<br/> Ex1: 12.50 
				</span>',
				isset( $this->opt['formule_base'] ) ? esc_attr( $this->opt['formule_base']) : ''
			);
		}

		public function formule_range1_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[formule_range1]" id="formule_range1" value="%s">
				<br/><span>Price for the range 1-20 sq units:
				<br/> Ex1: 2.50 
				</span>',
				isset( $this->opt['formule_range1'] ) ? esc_attr( $this->opt['formule_range1']) : ''
			);
		}

		public function formule_range2_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[formule_range2]" id="formule_range2" value="%s">
				<br/><span>Price for the range 21-40 sq units:
				<br/> Ex1: 2.50 
				</span>',
				isset( $this->opt['formule_range2'] ) ? esc_attr( $this->opt['formule_range2']) : ''
			);
		}

		public function formule_range3_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[formule_range3]" id="formule_range3" value="%s">
				<br/><span>Price for the range 41-60 sq units:
				<br/> Ex1: 2.50 
				</span>',
				isset( $this->opt['formule_range3'] ) ? esc_attr( $this->opt['formule_range3']) : ''
			);
		}

		public function formule_range4_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[formule_range4]" id="formule_range4" value="%s">
				<br/><span>Price for the range 61-80 sq units:
				<br/> Ex1: 2.50 
				</span>',
				isset( $this->opt['formule_range4'] ) ? esc_attr( $this->opt['formule_range4']) : ''
			);
		}

		public function formule_range5_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[formule_range5]" id="formule_range5" value="%s">
				<br/><span>Price for the range 81-100 sq units:
				<br/> Ex1: 2.50 
				</span>',
				isset( $this->opt['formule_range5'] ) ? esc_attr( $this->opt['formule_range5']) : ''
			);
		}


		public function labelvalue1_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[labelvalue1]" id="labelvalue1" value="%s">
				<br/><span>Label for the first slider</span>',
				isset( $this->opt['labelvalue1'] ) ? esc_attr( $this->opt['labelvalue1']) : ''
			);
		}
		public function labelvalue2_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[labelvalue2]" id="labelvalue2" value="%s">
				<br/><span>Label for the second slider</span>',
				isset( $this->opt['labelvalue2'] ) ? esc_attr( $this->opt['labelvalue2']) : ''
			);
		}
		public function labelresult_callback() {
			printf(
				'<input class="regular-text" type="text" name="rules_price_calculator__option_name[labelresult]" id="labelresult" value="%s">
				<br/><span>Label for the calculate result</span>',
				isset( $this->opt['labelresult'] ) ? esc_attr( $this->opt['labelresult']) : ''
			);
		}
		public function currencysymbol_callback() {
			printf(
				'<input class="regular-text" maxlength="1" type="text" name="rules_price_calculator__option_name[currencysymbol]" id="currencysymbol" value="%s">
				<br/><span>Currency symbol</span>',
				isset( $this->opt['currencysymbol'] ) ? esc_attr( $this->opt['currencysymbol']) : ''
			);
		}
		public function active_callback() {
			printf(
				'<input type="checkbox" name="rules_price_calculator__option_name[active]" id="active" value="active" %s> <label for="active">If checked the rules price calculator works</label>',
				( isset( $this->opt['active'] ) && $this->opt['active'] === 'active' ) ? 'checked' : ''
			);
		}
	
	}