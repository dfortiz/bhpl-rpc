<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly. 
}

class bhpepc_widget {

	private $admin=false;
	private $opt=null;
	private $enable=true;

	function __construct($id_admin=false){

		$this->admin=$id_admin;
		$this->rules_price_calculator_load_options('rules_price_calculator__option_name');
		
		if($this->enable){
			add_action( 'wp_enqueue_scripts', array($this,'widget_style' ), 50);
			add_action( 'woocommerce_before_add_to_cart_button', array($this,'rules_price_calculator_add_custom_fields' ));
			add_filter( 'woocommerce_add_cart_item_data', array($this,'rules_price_calculator_add_item_data' ));
			add_action( 'woocommerce_checkout_create_order_line_item', array($this,'rules_price_calculator_add_custom_order_line_item_meta' ));
			add_action( 'woocommerce_before_calculate_totals', array($this,'woocommerce_custom_price_to_cart_item' ), 1000);
			add_filter( 'woocommerce_get_price_html', function( $price, $product ) {
				if ( $this->rules_price_calculator_process() ) {
					return '';
				}
				return $price; // Return original price
			}, 10, 2 );

			add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
				if( array_key_exists('bhaarpc_value1', $cart_item) )
				{
					$item_data[] = array(
						'key'   => str_replace(":", "", $this->opt['labelvalue1']),
						'value' => $cart_item['bhaarpc_value1']
					);					
				}
				if( array_key_exists('bhaarpc_value2', $cart_item) )
				{
					$item_data[] = array(
						'key'   => str_replace(":", "", $this->opt['labelvalue2']),
						'value' => $cart_item['bhaarpc_value2']
					);					
				}
				return $item_data;
			}, 10, 2 );
		}
	}

	function woocommerce_custom_price_to_cart_item( $cart_object ) { 
		if( !WC()->session->__isset( "reload_checkout" )) {
			foreach ( $cart_object->cart_contents as $key => $value ) {
				if ( isset (  $value['bhaarpc_value1']  )  && isset (  $value['bhaarpc_value2']  ) ) {

					$remainArea =  $value['bhaarpc_value1'] * $value['bhaarpc_value2'] - 1;
					$formule_base = 1 * $this->opt['formule_base'];
					$formule_range1 = $this->opt['formule_range1'];
					$formule_range2 = $this->opt['formule_range2'];
					$formule_range3 = $this->opt['formule_range3'];
					$formule_range4 = $this->opt['formule_range4'];
					$formule_range5 = $this->opt['formule_range5'];

					if ( $remainArea < 21) {
						$remainArea = $remainArea * $formule_range1; 
					} else if ( $remainArea < 41) {
						$remainArea = $remainArea * $formule_range2; 
					} else if ( $remainArea < 61) {
						$remainArea = $remainArea * $formule_range3; 
					} else if ($remainArea < 81) {
						$remainArea = $remainArea * $formule_range4; 
					} else {
						$remainArea = $remainArea * $formule_range5; 
					}
//frank
					$result1 =  1 * $formule_base + $remainArea;				

					$value['data']->set_price( $result1 );
				}
			}  
		}  
	}
	
	function rules_price_calculator_load_options(){
		$opt = get_option( 'rules_price_calculator__option_name' );
		$this->opt = [];
		$this->opt['productid'] = isset($opt['productid'])?$opt['productid']:'';
		$this->opt['formule'] = isset($opt['formule'])?$opt['formule']:'';
		$this->opt['formule_base'] = isset($opt['formule_base'])?$opt['formule_base']:'';
		$this->opt['formule_range1'] = isset($opt['formule_range1'])?$opt['formule_range1']:'';
		$this->opt['formule_range2'] = isset($opt['formule_range2'])?$opt['formule_range2']:'';
		$this->opt['formule_range3'] = isset($opt['formule_range3'])?$opt['formule_range3']:'';
		$this->opt['formule_range4'] = isset($opt['formule_range4'])?$opt['formule_range4']:'';
		$this->opt['formule_range5'] = isset($opt['formule_range5'])?$opt['formule_range5']:'';
		$this->opt['labelvalue1'] = isset($opt['labelvalue1'])?$opt['labelvalue1']:'';
		$this->opt['labelvalue2'] = isset($opt['labelvalue2'])?$opt['labelvalue2']:'';
		$this->opt['labelresult'] = isset($opt['labelresult'])?$opt['labelresult']:'';
		$this->opt['currencysymbol'] = isset($opt['currencysymbol'])?$opt['currencysymbol']:'';
		$this->opt['active']= isset($opt['active']);
		$this->enable=$this->opt['active'];
	}

	function rules_price_calculator_process() {
		try {
			$a = explode(",", $this->opt['productid']);
			if ( count($a) == 0 ) return false;
			if ( count($a) == 1 ) return $a[0] == get_the_ID();
			if ( count($a) > 1 ) return in_array( get_the_ID(), $a );
		} catch (Exception $e) {
			return false;
		}
	}

	function widget_style($page){
		wp_enqueue_style( '_style', BH_PLGN_RPC_URL.'assets/css/bh-epc-front.css');
	}

	function rules_price_calculator_add_custom_fields()
	{
		// if ( in_array( get_the_ID(), [10, 12]) ) {
		if ( $this->rules_price_calculator_process() ) {
			global $product;
			ob_start();
			
			echo "<script>let qproductid='#bhepcprod-" . get_the_ID() . "';</script>";
			echo "<script>let formule_base='" . $this->opt['formule_base'] . "';</script>";
			echo "<script>let formule_range1='" . $this->opt['formule_range1'] . "';</script>";
			echo "<script>let formule_range2='" . $this->opt['formule_range2'] . "';</script>";
			echo "<script>let formule_range3='" . $this->opt['formule_range3'] . "';</script>";
			echo "<script>let formule_range4='" . $this->opt['formule_range4'] . "';</script>";
			echo "<script>let formule_range5='" . $this->opt['formule_range5'] . "';</script>";
			echo "<div class='bhepc-container wdm-custom-fields' id='bhepcprod-" . get_the_ID() . "'>";
			?>
				<input type="hidden" name="bhaarpc_value1">
				<input type="hidden" name="bhaarpc_value2">
				<input type="hidden" id="bhaarpc_price" name="bhaarpc_price">
					<div class="slidecontainer">
						<h3><?php echo $this->opt['labelvalue1'] ; ?> <span class="bhepc-val1"></span></h3>
						<input type="range" min="1" max="10" value="5" step="0.5" class="slider" id="bhaarpc_value1" name="bhaarpc_value1">
					</div>
					<div class="slidecontainer">
						<h3><?php echo $this->opt['labelvalue2'] ; ?> <span class="bhepc-val2"></span></h3>
						<input type="range" min="1" max="10" value="5" step="0.5" class="slider" id="bhaarpc_value2" name="bhaarpc_value2">
					</div>
					<div class="slidecontainer">
						<h2><?php echo $this->opt['labelresult'] ; ?> <span class="bhepc-val3"> </span></h2><br/>
					</div>
					<script>
						let slider1 = document.getElementById("bhaarpc_value1");
						let slider2 = document.getElementById("bhaarpc_value2");
						// Update the fields
						function bhaarpc_update_fields() {
							document.querySelector( qproductid + " .bhepc-val1" ).innerHTML = slider1.value;
							document.querySelector( qproductid + " .bhepc-val2" ).innerHTML = slider2.value;
							let result1 = 0;
							let remainArea =  slider1.value * slider2.value - 1;

							if ( remainArea < 21) {
								remainArea = (remainArea * formule_range1 * 10 / 10).toFixed(2); 
							} else if ( remainArea < 41) {
								remainArea = (remainArea * formule_range2 * 10 / 10).toFixed(2); 
							} else if ( remainArea < 61) {
								remainArea = (remainArea * formule_range3 * 10 / 10).toFixed(2); 
							} else if (remainArea < 81) {
								remainArea = (remainArea * formule_range4 * 10 / 10).toFixed(2); 
							} else {
								remainArea = (remainArea * formule_range5 * 10 / 10).toFixed(2); 
							}

							result1 = 1 * formule_base + 1 * remainArea;
							result1 = result1.toFixed(2);

							// frank
							document.getElementById("bhaarpc_price").value = result1;
							document.querySelector(qproductid +" .bhepc-val3").innerHTML =  '<?php echo $this->opt['currencysymbol'] ; ?>' + result1;
						}														
						slider1.oninput = function() {
							bhaarpc_update_fields();
						}
						slider2.oninput = function() {
							bhaarpc_update_fields();
						}
						bhaarpc_update_fields();
					</script>
				</div>
				<div class="clear"></div>
			<?php
			$content = ob_get_contents();
			ob_end_flush();
			return $content;
		}
	}

	function rules_price_calculator_add_item_data($cart_item_data)
	{
		if(isset($_REQUEST['bhaarpc_value1']))
		{
			$cart_item_data['bhaarpc_value1'] = sanitize_text_field($_REQUEST['bhaarpc_value1']);
		}
		if(isset($_REQUEST['bhaarpc_value2']))
		{
			$cart_item_data['bhaarpc_value2'] = sanitize_text_field($_REQUEST['bhaarpc_value2']);
		}
		return $cart_item_data;
	}

	function rules_price_calculator_add_custom_order_line_item_meta($item, $cart_item_key, $values, $order)
	{
		if(array_key_exists('bhaarpc_value1', $values))
		{
			$item->add_meta_data('_bhaarpc_value1',$values['bhaarpc_value1']);
		}
		if(array_key_exists('bhaarpc_value2', $values))
		{
			$item->add_meta_data('_bhaarpc_value2',$values['bhaarpc_value2']);
		}
	}

	
}
?>