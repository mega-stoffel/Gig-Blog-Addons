<?php
/*
Plugin Name:  Gig-Blog Addons
Plugin URI:   https://github.com/mega-stoffel/gig-blog-addons
Description:  just a few addons to visualize on the gig-blog website
Version:      0.1
Author:       X-tof Hoyer
Author URI:   https://gig-blog.net
*/

register_activation_hook( __FILE__ , 'tallbike_install' );
//register_activation_hook( __FILE__ , 'tallbike_install_data' );

// -----------------------------------
//       S H O R T C O D E S
// -----------------------------------
require_once( 'gigblog-shortcodes.php' );
add_shortcode('gb_archive', 'gb_archive');
