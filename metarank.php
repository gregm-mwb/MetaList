<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://metamoron.io
 * @since             1.0.0
 * @package           metalist
 *
 * @wordpress-plugin
 * Plugin Name:       MetaList
 * Plugin URI:        metamoron.io
 * Description:       Measures interaction on page to order results. Renamed MetaList to avoid confusion with an existing platform.
 * Version:           1.0.0
 * Author:            Greg Molloy
 * Author URI:        https://metamoron.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       metalist
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'metalist_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-metalist-activator.php
 */
function activate_metalist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-metalist-activator.php';
	metalist_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-metalist-deactivator.php
 */
function deactivate_metalist() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-metalist-deactivator.php';
	metalist_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_metalist' );
register_deactivation_hook( __FILE__, 'deactivate_metalist' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-metalist.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */




function add_ranking_actions() {
	?>
<script id="actions">
jQuery(document).ready(function() {
var formData = 	{
        action: 'countaction',
        id: <?php echo get_the_ID(); ?>,
	    }


	
jQuery('#primary').one('click',function() {
jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList');
        });	
});	
	
jQuery(window).one('scroll',function() {
jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList');
        });	
});
window.setTimeout(function(){
 jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList');
        });	
}, 10000);

window.setTimeout(function(){
 jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList');
        });
}, 30000);

window.setTimeout(function(){
 jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList');
        });
}, 60000);
});
</script>
<?php
}
add_action( 'wp_footer', 'add_ranking_actions' );



add_action('wp_ajax_countaction', 'countaction');

add_action('wp_ajax_nopriv_countaction', 'countaction');
function countaction() {
	$postID = intval( $_POST['id'] );
    global $wpdb; // this is how you get access to the database

    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
     $count++;    
        update_post_meta($postID, $count_key, $count);

    }
	$trending_key = 'wpb_trending_count';
$trend = get_post_meta($postID, $trending_key, true);
    if($trend ==''){
        $trend = 0;
        delete_post_meta($postID, $trending_key);
        add_post_meta($postID, $trending_key, '0');
    }else{
     $trend++;    
        update_post_meta($postID, $trending_key, $trend);

    }
}
/* still working on this

wp_schedule_event( time(), 60, 'wpb_reset_trending' );


function wpb_reset_trending() {
 $trending_key = 'wpb_trending_count';
    $args = array(
        'post_type' => 'post', // Only get the posts
        'post_status' => 'publish', // Only the posts that are published
        'posts_per_page'   => -1 // Get every post
    );
    $posts = get_posts($args);
    foreach ( $posts as $post ) {
        // Run a loop and update every meta data
        update_post_meta( $post->ID, 'wpb_trending_count', '0' );
    }
}


*/



function run_metalist() {

	$plugin = new metalist();
	$plugin->run();

}
run_metalist();
