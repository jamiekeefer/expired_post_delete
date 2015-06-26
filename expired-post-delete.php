<?php
/**
 * Plugin Name: Expired Post Delete
 * Plugin URI: http://keefermedia.com/expired-post-delete/
 * Description: Delete expired posts based on date field custom data.
 * Version: 1.0.0
 * Author: Jamie Keefer
 * Author URI: http://keefermedia.com
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: A short license name. Example: GPL2
 */
 
 /*  Copyright 2014  Jamie Keefer  (email : jamie@keefermedia.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// expired_post_delete hook fires when the Cron is executed

add_action( 'expired_post_delete', 'delete_expired_posts' );
 
// This function will run once the 'expired_post_delete' is called

function delete_expired_posts() {
 
$todays_date = current_time('mysql');

$args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'meta_key' => 'date',
		'meta_query' => array(
			array(
				'key' => 'date',
				'value' => $todays_date,
				'type' => 'DATE',
				'compare' => '<'
				)
		)
);
     $posts = new WP_Query( $args );

	// The Loop
	//if ( $posts->have_posts() ) {
	//	
	//	while ( $posts->have_posts() ) {
	//		$posts->the_post();
	//		wp_delete_post(get_the_ID(), true);			
	//	}
	//
	//} 

	foreach ($posts as $post){
	wp_delete_post($post->ID, true);
	}

/* Restore original Post Data */
wp_reset_postdata();
}

// Add function to register event to WordPress init
add_action( 'init', 'register_daily_post_delete_event');
 
// Function which will register the event
function register_daily_post_delete_event() {
    // Make sure this event hasn't been scheduled
    if( !wp_next_scheduled( 'expired_post_delete' ) ) {
        // Schedule the event
        wp_schedule_event( time(), 'daily', 'expired_post_delete' );
    }
}

?>
