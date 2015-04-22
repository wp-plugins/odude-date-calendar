<?php
/**
 * @package ODude Date
 */
/*
Plugin Name: ODude Date (Calendar)
Plugin URI: http://odude.com/
Description: Listing events as calendar.
Version: 1.0.0
Author: ODude Network
Author URI: http://odude.com/
License: GPLv2 or later
Text Domain: odudedate
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'ODUDEDATE_VERSION', '1.0.0' );
define( 'ODUDEDATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ODUDEDATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ODUDEDATE_TEMPLATES_PATH', ODUDEDATE_PLUGIN_DIR . 'templates' );
//Library
require_once(ODUDEDATE_PLUGIN_DIR . '/include/lib.php');


 $site_url = network_site_url( '/' );

 $default_code=get_option('dfcode');
 $default_name=ODudeGroup('gname',$default_code,'admin');
 $pro_active=get_option('fbcard');
 //$keylink=get_option('key_link');
$keylink=$_SERVER['SERVER_NAME'];
 
if(isset($_GET['default']))
{
	$cval=$_GET['default'];
	$czone=$_GET['zone'];
	setcookie( "odude_cal", $cval, strtotime( '+30 days' ) ,'/');
	setcookie( "odude_cal_zone", $czone, strtotime( '+30 days' ) ,'/');
	
		
	
}



function odudedate_styles() 
{
	wp_register_style( 'odude-date', plugins_url( 'css/pure-min.css', __FILE__ ) );
	wp_register_style( 'odude-css', plugins_url( 'css/odudedate.css', __FILE__ ) );
	wp_register_style( 'odude-css_r', plugins_url( 'css/grids-responsive-min.css', __FILE__ ) );
	//wp_register_style( 'odude-date', plugins_url( 'odude-date/css/pure-min.css' ) );
	//wp_register_style( 'odude-css', plugins_url( 'odude-date/css/odudedate.css' ) );
	//wp_register_style( 'odude-css_r', plugins_url( 'odude-date/css/grids-responsive-min.css' ) );
	wp_enqueue_style( 'odude-date' );
	wp_enqueue_style( 'odude-css' );
	wp_enqueue_style( 'odude-css_r' );
}
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'odudedate_styles' );

//Install/Uninstall ODude Date
require_once(ODUDEDATE_PLUGIN_DIR . '/include/setup.php');
register_activation_hook(__FILE__,'install_table_odudedate');
register_uninstall_hook(__FILE__,'oddroptables');


//Main page of Date
require_once(ODUDEDATE_PLUGIN_DIR . '/include/main.php');

//Settings
require_once(ODUDEDATE_PLUGIN_DIR . '/include/options.php');

function odudedate()
{


//ODude Date Admin
require_once(ODUDEDATE_PLUGIN_DIR . '/include/post.php');

}
function odudedate_stats()
{


//ODude Date Admin
require_once(ODUDEDATE_PLUGIN_DIR . '/include/stats.php');

}

function odudedate_dash()
{
//ODude Date Dashboard
require_once(ODUDEDATE_PLUGIN_DIR . '/include/dash.php');

}

//Menu in action
function odudedatecreatemenu()
{
	
	add_menu_page( 'ODude Date', 'ODude Date', 'manage_options', 'odudedate_dash', 'odudedate_dash',  plugins_url( 'icon.png', __FILE__ ), 6 );
	//add_menu_page( 'ODude Date', 'ODude Date', 'manage_options', 'odudedate_dash', 'odudedate_dash', plugins_url( 'odude-date/icon.png' ), 6 );
	add_submenu_page( 'odudedate_dash', 'Post', 'Post', 'manage_options', 'odudedate', 'odudedate' );
	add_submenu_page( 'odudedate_dash', 'Last Updated', 'Last Updated', 'manage_options', 'odudedate_stats', 'odudedate_stats' );
	add_submenu_page( 'odudedate_dash', 'Settings', 'Settings', 'manage_options', 'odudedate_setting', 'odudedate_setting' );
	add_action( 'admin_init', 'odudedate_register_mysettings' );
 
    add_menu_page('ODude Date User', 'ODude Date User', 'author' , 'odudedate_e', 'odudedate_dash');
	add_submenu_page( 'odudedate_e', 'Post User', 'Post User', 'author', 'odudedate', 'odudedate' );

} 
add_action('admin_menu', 'odudedatecreatemenu');


function add_query_vars($aVars) 
{
    //$aVars[] = "sid";    // represents the name of the product category as shown in the URL
	array_push($aVars,'sday','smonth','syear','scountry','sid');
    return $aVars;
}

add_filter('query_vars', 'add_query_vars');

function add_rewrite_rules($aRules)
 {
	//if(get_query_var('name')=='nepal') 
    $aNewRules = array('calendar/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=calendar&sday=$matches[1]&smonth=$matches[2]&syear=$matches[3]&scountry=$matches[4]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}

add_filter('rewrite_rules_array', 'add_rewrite_rules');

function holiday_rewrite_rules($aRules)
 {
	//if(get_query_var('name')=='nepal') 
    $aNewRules = array('holiday/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=holiday&smonth=$matches[1]&syear=$matches[2]&scountry=$matches[3]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}
add_filter('rewrite_rules_array', 'holiday_rewrite_rules');


function o_detail_rewrite_rules($aRules)
 {
	
    $aNewRules = array('calendar/([^/]+)/([^/]+)/?$' => 'index.php?pagename=calendar&sid=$matches[1]&syear=$matches[2]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}
add_filter('rewrite_rules_array', 'o_detail_rewrite_rules');


function osearch_rewrite_rules($aRules)
 {
	
    $aNewRules = array('search/([^/]+)/?$' => 'index.php?pagename=search&scountry=$matches[1]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}
add_filter('rewrite_rules_array', 'osearch_rewrite_rules');

//Setting the title

function DateTimeTitle()
{
global $wp_query;
global $wpdb;
	if(isset($wp_query->query_vars['sday']))
	{
	$day=$wp_query->query_vars['sday'];
	$month=$wp_query->query_vars['smonth'];
	$year=$wp_query->query_vars['syear'];
		if($year=="0")
		$year=date('Y');
	$country=$wp_query->query_vars['scountry'];
	$datefor="$year-$month-$day";
	
	
	$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day =%d and month=%d and (year=%d or year='0') and country=%s and category='general'",array($day,$month,$year,$country));
	
	$getgalimages=$wpdb->get_results($sql);
	
	//$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate WHERE day = '$day' and month='$month' and (year='".$year."' or year='0') and country='$country' and category='general'");

	$d="";
		if(count($getgalimages))
			{
				foreach($getgalimages as $val)
				{
					$event = $val->event;
					$d.=$event." | ";
				
				}
				return $d."  ".conName($country)." ";
			
			}
			else
			{
			return iso2long($datefor)."  ".conName($country)."";
			}
	}
	else if(isset($wp_query->query_vars['sid']))
	{
	return rawurldecode(basename($wp_query->query_vars['syear']))." ";
	}
	else
	{
		return "What is today?";
	}
	
	
}
add_filter( 'wp_title', 'DateTimeTitle');


function date_actionhook_odudehead()
{
global $wp_query;
global $wpdb;
global  $site_url;
global $pro_active;
$upload_dir = wp_upload_dir();

if (is_page('calendar'))
{
	global $wp_query;

	if(isset($wp_query->query_vars['sday']))
	{
	$day=$wp_query->query_vars['sday'];
	$month=$wp_query->query_vars['smonth'];
	$year=$wp_query->query_vars['syear'];
	$country=$wp_query->query_vars['scountry'];
	$value= getEventsList($day,$month,$year,'general',$country);
	echo '<meta name="description" content="'.$value.'" />' . "\n";
		
	}
	else if(isset($wp_query->query_vars['sid']))
	{
		$sid=$wp_query->query_vars['sid'];
	
			$sql = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."odudedate WHERE id = %d and Publish='1'",array($sid));
			$getgalimages = $wpdb->get_results($sql);
			//$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."odudedate WHERE id = '".$sid."' and Publish='1'");
			if(count($getgalimages))
			{
				foreach($getgalimages as $val)
				{
				$event = $val->event;
				$extra3=$val->extra3;
				$extra1=$val->extra1;
				$extra2=$val->extra2;
				$id=$val->id;
				if($extra3!="")
				{
				//$extra3='<meta property="og:image" content="'.$site_url.'wp-content/uploads/odude-date/'.$id.'.jpg"/>';
				$extra3='<meta property="og:image" content="'.$upload_dir['baseurl'].'/odude-date/'.$id.'.jpg"/>';
				}
				else
				{
					if($pro_active=='1')
					$extra3='<meta property="og:image" content="'.dynamicImage($event,$id,false).'"/>';
				}
				
			
				$meta='								
						<meta property="og:title" content="'.$event.'"/>
						<meta property="og:type" content="article"/>
						'.$extra3.'
						<meta property="og:url" content="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'"/>
						<meta property="og:description" content="'.$extra1.' '.$extra2.' More Details..."/>
						<meta property="og:site_name" content="'.get_bloginfo('name').'"/>
						<meta property="fb:app_id" content="'.get_option('fbid').'"/> ';
						echo $meta;
				}
			
			}
	
	}
	
	else
	{
		echo '<meta name="description" content="What is today?" />' . "\n";
	}			 
				 
}	
		
				
}


add_action( 'wp_head', 'date_actionhook_odudehead' );


?>
