<?php

class DD_List_WC_Backorders{

    protected $plugin_name;

    protected $version;
    
    public function __construct(){
        if ( defined( 'DD_LIST_BACKORDERS_WC_VERSION' ) ) {
			$this->version = DD_LIST_BACKORDERS_WC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'list-backorders-for-woocommerce';

        add_action( 'admin_menu', array(&$this , 'register_backorder_page' ) );
        if( isset( $_GET['page'] ) && 'manage-backorder' == $_GET['page'] ){
            add_action( 'admin_enqueue_scripts', array(&$this, 'enqueue_scripts' ) );
        }
    }

    /**
	 * Register the JavaScript and CSS for the admin area.
	 *
	 * @since    2.0.0
	 */

	public function enqueue_scripts() {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-list-backorders.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name . 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);
        wp_enqueue_script( $this->plugin_name . 'data-tables', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array('jquery'), '1.11.14', true);
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-list-backorders.min.js', array( 'jquery' ), '2.0.0', true );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        $report_title = apply_filters('dd_list_backorders_report_title', date('Ymd') . '_backorder_list');
		$report_charset = apply_filters( 'dd_list_backorders_report_charset', 'UTF-8' );
        wp_localize_script($this->plugin_name, 'dd_backorders', array(
			'report_charset' => $report_charset,
			'report_title'   => $report_title,
			)
		);
    }

    public function register_backorder_page() {
        $backorders = __('Items', 'woocommerce') . ' ' . __( 'Backordered', 'woocommerce' );
        add_submenu_page( 'woocommerce', 'View Backorders List | By Duck Diver', $backorders , 'manage_woocommerce', 'manage-backorder', array($this, 'manage_backorder_callback' ) );
    }

    function manage_backorder_callback() {
        require plugin_dir_path( __FILE__ ) . 'list-backorder-admin-callback.php';
    }

}
