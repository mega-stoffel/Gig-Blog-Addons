<?php
/*
Plugin Name:  Gig-Blog Addons
Plugin URI:   https://github.com/mega-stoffel/gig-blog-addons
Description:  just a few addons to visualize on the gig-blog website
Version:      0.2
Author:       X-tof Hoyer
Author URI:   https://gig-blog.net
*/

// -----------------------------------
//       S H O R T C O D E S
// -----------------------------------
require_once( 'gigblog-shortcodes.php' );

add_shortcode('gb_archive', 'gb_archive');
add_shortcode('gb_randomPost', 'gb_randomPost');
add_shortcode('gb_latestPost', 'gb_latestPost');
add_shortcode('gb_randomPage', 'gb_randomPage');
add_shortcode('gb_postCount', 'gb_postCount');

// Shortcodes with some parameters:
add_shortcode('gb_archive_year', 'gb_archive_year');
add_shortcode('gb_archive_person', 'gb_archive_person');
