<?php
 $user_ID = get_current_user_id();
 //echo "user id =".$user_ID;
$sql = "SELECT pack_id,pack_horse_limit,pack_video_limit,pack_imagelimit FROM wp_cp_ad_packs LEFT JOIN wp_usermeta ON wp_cp_ad_packs.pack_id=wp_usermeta.meta_value where wp_usermeta.user_id = $user_ID;";
		$results = $wpdb->get_results($sql);
		//var_dump($results);
		foreach ( $results as $results ) {
		$imagelimit= $results->pack_imagelimit;
		$videolimit= $results->pack_video_limit;
		$horselimit= $results->pack_horse_limit;		
		$pack_id=$results->pack_id;
		}
/**
 * Theme functions file
 *
 * DO NOT MODIFY THIS FILE. Make a child theme instead: http://codex.wordpress.org/Child_Themes
 *
 * @package ClassiPress
 * @author AppThemes
 */

// Constants
define( 'CP_VERSION', '3.3.2' );
define( 'CP_DB_VERSION', '2103' );

define( 'APP_POST_TYPE', 'ad_listing' );
define( 'APP_TAX_CAT', 'ad_cat' );
define( 'APP_TAX_TAG', 'ad_tag' );
define( 'CP_LOGIN', get_site_url().'/login' );//code added by rj
define( 'CP_ITEM_LISTING', 'ad-listing' );
define( 'CP_ITEM_MEMBERSHIP', 'membership-pack' );

define( 'APP_TD', 'classipress' );

global $cp_options;

// Legacy variables - some plugins rely on them
$app_theme = 'ClassiPress';
$app_abbr = 'cp';
$app_version = '3.3.2';
$app_db_version = 2103;
$app_edition = 'Ultimate Edition';


// Framework
require_once( dirname( __FILE__ ) . '/framework/load.php' );
require_once( APP_FRAMEWORK_DIR . '/includes/stats.php' );
require_once( APP_FRAMEWORK_DIR . '/admin/class-meta-box.php' );

APP_Mail_From::init();

// define the transients we use
$app_transients = array( 'cp_cat_menu' );

// define the db tables we use
$app_db_tables = array( 'cp_ad_fields', 'cp_ad_forms', 'cp_ad_geocodes', 'cp_ad_meta', 'cp_ad_packs', 'cp_ad_pop_daily', 'cp_ad_pop_total', 'cp_coupons', 'cp_order_info' );



register_sidebar( array(
    'name' => __( 'Custom Widget Area'),
    'id' => 'custom-widget-area',
    'description' => __( 'An optional custom widget area for your site', 'twentyten' ),
    'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
    'after_widget' => "</li>",
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
) );



// register the db tables
foreach ( $app_db_tables as $app_db_table ) {
	scb_register_table( $app_db_table );
}
scb_register_table( 'app_pop_daily', 'cp_ad_pop_daily' );
scb_register_table( 'app_pop_total', 'cp_ad_pop_total' );


$load_files = array(
	'payments/load.php', 'options.php', 'appthemes-functions.php', 'actions.php', 'comments.php',
	'core.php', 'cron.php', 'deprecated.php', 'enqueue.php', 'emails.php',
	'functions.php', 'hooks.php', 'payments.php', 'profile.php', 'search.php',
	'security.php', 'stats.php', 'views.php', 'widgets.php',
);
appthemes_load_files( dirname( __FILE__ ) . '/includes/', $load_files );

$load_classes = array(
	'CP_Blog_Archive', 'CP_Ads_Home', 'CP_Ads_Categories', 'CP_Add_New', 'CP_Ad_Single',
	'CP_Edit_Item', 'CP_Order_Summary', 'CP_Membership', 'CP_User_Dashboard', 'CP_User_Profile',
);
appthemes_add_instance( $load_classes );


// Admin only
if ( is_admin() ) {
	require_once( APP_FRAMEWORK_DIR . '/admin/importer.php' );

	$load_files = array(
		'admin.php', 'dashboard.php', 'enqueue.php', 'install.php', 'importer.php',
		'options.php', 'settings.php', 'system-info.php', 'updates.php',
	);
	appthemes_load_files( dirname( __FILE__ ) . '/includes/admin/', $load_files );

	$load_classes = array(
		'CP_Theme_Dashboard',
		'CP_Theme_Settings_General' => $cp_options,
		'CP_Theme_Settings_Emails' => $cp_options,
		'CP_Theme_Settings_Pricing' => $cp_options,
		'CP_Theme_System_Info',
	);
	appthemes_add_instance( $load_classes );
}

// Frontend only
if ( ! is_admin() ) {

	cp_load_all_page_templates();
}

// Constants
define( 'CP_DASHBOARD_URL', get_permalink( CP_User_Dashboard::get_id() ) );
define( 'CP_PROFILE_URL', get_permalink( CP_User_Profile::get_id() ) );
define( 'CP_EDIT_URL', get_permalink( CP_Edit_Item::get_id() ) );
define( 'CP_ADD_NEW_URL', get_permalink( CP_Add_New::get_id() ) );
define( 'CP_MEMBERSHIP_PURCHASE_URL', get_permalink( CP_Membership::get_id() ) );
define( 'CP_MEMBERSHIP_SIGNUP', get_site_url().'/memberships-log' );


// Theme supports
add_theme_support( 'app-versions', array(
	'update_page' => 'admin.php?page=app-settings&firstrun=1',
	'current_version' => CP_VERSION,
	'option_key' => 'cp_version',
) );

add_theme_support( 'app-wrapping' );

add_theme_support( 'app-login', array(
	'login' => 'tpl-login.php',
	//'login' => 'wp-login.php',
	'register' => 'tpl-registration.php',
	'recover' => 'tpl-password-recovery.php',
	'reset' => 'tpl-password-reset.php',
	'redirect' => $cp_options->disable_wp_login,
	'settings_page' => 'admin.php?page=app-settings&tab=advanced',
) );

add_theme_support( 'app-feed', array(
	'post_type' => APP_POST_TYPE,
	'blog_template' => 'index.php',
	'alternate_feed_url' => $cp_options->feedburner_url,
) );

add_theme_support( 'app-payments', array(
	'items' => array(
		array(
			'type' => CP_ITEM_LISTING,
			'title' => __( 'Listing', APP_TD ),
			'meta' => array(),
		),
		array(
			'type' => CP_ITEM_MEMBERSHIP,
			'title' => __( 'Membership', APP_TD ),
			'meta' => array(),
		),
	),
	'items_post_types' => array( APP_POST_TYPE ),
	'options' => $cp_options,
) );
function add_horses()
{
 global $wpdb;  
 global $cp_options;
 $data1 = $_POST['data'];
 $user_id= $data1['user_id'];
 $show_id = $data1['show_id'];
 $checkedValues = $data1['checkedValues'];
	 foreach($checkedValues as  $pstid)
	 {
		$res="SELECT post_id FROM horseshows_meta WHERE post_id='$pstid' AND show_id='$show_id'";
		$result = $wpdb->get_results($res);
		//var_dump($result);
		if($result)
		{
			//echo "test";
			continue;
		}
		else
		{
		//var_dump($result);
			$sql="INSERT INTO horseshows_meta (show_id, post_id,user_id) VALUES ('$show_id', '$pstid','$user_id')";
			$result_status = $wpdb->query($sql);
			
		}
		
	 }
	 echo "inserted";
}
add_action( 'wp_ajax_add_horses', 'add_horses' );
add_action( 'wp_ajax_nopriv_add_horses', 'add_horses' );

add_theme_support( 'app-price-format', array(
	'currency_default' => $cp_options->currency_code,
	'currency_identifier' => $cp_options->currency_identifier,
	'currency_position' => $cp_options->currency_position,
	'thousands_separator' => $cp_options->thousands_separator,
	'decimal_separator' => $cp_options->decimal_separator,
	'hide_decimals' => $cp_options->hide_decimals,
) );

add_theme_support( 'app-plupload', array(
	'max_file_size' => $cp_options->max_image_size,
	'allowed_files' => $imagelimit,
	'disable_switch' => false,
) );

add_theme_support( 'app-stats', array(
	'cache' => 'today',
	'table_daily' => 'cp_ad_pop_daily',
	'table_total' => 'cp_ad_pop_total',
	'meta_daily' => 'cp_daily_count',
	'meta_total' => 'cp_total_count',
) );

add_theme_support( 'post-thumbnails' );

add_theme_support( 'automatic-feed-links' );

// AJAX
add_action( 'wp_ajax_nopriv_ajax-tag-search-front', 'cp_suggest' );
add_action( 'wp_ajax_ajax-tag-search-front', 'cp_suggest' );

add_action( 'wp_ajax_nopriv_dropdown-child-categories', 'cp_addnew_dropdown_child_categories' );
add_action( 'wp_ajax_dropdown-child-categories', 'cp_addnew_dropdown_child_categories' );



add_action("wp_ajax_nopriv_custom_select_shows_function", "custom_select_shows_function");
add_action("wp_ajax_custom_select_shows_function", "custom_select_shows_function");


function custom_select_shows_function(){
	global $wpdb;
	$showname=isset($_GET['term'])?$_GET['term']:'';
	$resultarr = $wpdb->get_results("SELECT name_show FROM horseshows WHERE name_show like '".$showname."%'");
	
	$json = array();
	foreach($resultarr as $result) {
		array_push($json, $result->name_show); //obviously this is unique to your application and not this verbatim
	}
	echo json_encode($json);
	
	die();
}	









// Image sizes
set_post_thumbnail_size( 100, 100 ); // normal post thumbnails
add_image_size( 'blog-thumbnail',  180, 200, true ); // blog post thumbnail size
add_image_size( 'sidebar-thumbnail', 180, 200, true ); // sidebar blog thumbnail size
add_image_size( 'ad-slider', 180, 200, true ); // sidebar blog thumbnail size
add_image_size( 'ad-feater-slider', 180, 200, true ); // sidebar blog thumbnail size
add_image_size( 'ad-thumb', 180, 200, true );
add_image_size( 'ad-small', 180, 200, true );
add_image_size( 'ad-medium', 180, 200, true );
add_image_size( 'ad-large', 180, 200, true );


// Set the content width based on the theme's design and stylesheet.
// Used to set the width of images and content. Should be equal to the width the theme
// is designed for, generally via the style.css stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 500;

show_admin_bar( false ); //hide wp-admin toolbar from front end
add_filter( 'jpeg_quality', create_function( '', 'return 100;' ) );
	
if (!function_exists('clear_nextend_uniqid_cookie')) {
    function clear_nextend_uniqid_cookie(){
        setcookie( 'nextend_uniqid',' ', time() - YEAR_IN_SECONDS, '/', COOKIE_DOMAIN );
        return 0;
    }
}

add_action('clear_auth_cookie', 'clear_nextend_uniqid_cookie');

appthemes_init();



