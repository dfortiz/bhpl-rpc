<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include(BH_PLGN_RPC_PATH."/inc/BH_PLGN_RPC-widget.php");

//Class for the setup plugin menu and initialization 
class bhpepc_main extends bhpepc_widget {

    function __construct(){
        
        //Setup admin panel
        if(is_admin()){
            parent::__construct(true);
            $this->bhpepc_admin_area();
            
        }
        else {
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                parent::__construct(false);
            }
        }
    }

     function bhpepc_admin_area(){
        //Call admin area class 
        include(BH_PLGN_RPC_PATH."/inc/BH_PLGN_RPC-admin.php");
        new bhpepc_admin($this);
    }
    
}

