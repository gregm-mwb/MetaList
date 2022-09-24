<?php
/**
 * Helper functions.
 *
 */
class m_Base {
	/**
	 * Helper for using prefixes for all references.
	 */
	public function setPrefix($name) {
		return ((strpos($name, $this->config['prefix']) === 0) ? '' : $this->config['prefix']) . $this->config['prefixSeparator'] . $name;
	}

	/**
	 * Helper for getting prefixed options.
	 */
	public function getOption($name, $default = null) {
		$ret = get_option($this->setPrefix($name));
		if(!$ret && $default) {
			$ret = $default;
		}
		return $ret;
	}
	
	/**
	 * Helper for adding/updating prefixed options.
	 */
	public function setOption($name, $value) {
		return ($this->getOption($name, '') === '') ? 
			add_option($this->setPrefix($name), $value) : 
			update_option($this->setPrefix($name), $value);
	}
	
}




function add_ranking_actions() {
	?>
<script id="actions">
jQuery(document).ready(function() {
var formData = 	{
        action: 'countaction',
        id: <?php echo get_the_ID(); ?>,
	    }

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

	
jQuery('#primary').one('click',function() {
jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList Click');
        });	
});	
	
jQuery(window).one('scroll',function() {
jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList Scroll');
        });	
});
window.setTimeout(function(){
 jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList Time');
        });	
}, 10000);

window.setTimeout(function(){
 jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList Time');
        });
}, 30000);

window.setTimeout(function(){
 jQuery.post(ajaxurl, formData, function(response) {
            console.log('MetaList Time');
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


// Reset Trending Every 12 hours
wp_schedule_event( time(), 43200, 'wpb_reset_trending' );
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


// Order the categories by popularity leave posts page in date order

add_action( 'pre_get_posts', 'metalink_filter_category_query' );
function metalink_filter_category_query( $query ) {

    // only modify front-end category archive pages

    if(!is_home() && is_category()  && !is_admin() && $query->is_main_query() ) {
		
        $query->set( 'meta_key','wpb_post_views_count' );
	$query->set( 'orderby','meta_value_num' );
        $query->set( 'order','DESC' );
    }
}




