<?php

/**
 * File triggered to uninstall the plugin
 */

 // safety measure so that only worpress can activate this file
if (! defined('WP_UNINSTALL_PLUGIN'))
{
    die;
}

//clear all data from DB
//Method 1 -- get all posts and delete them
$books = get_post( array('post_type' => 'book', 'numberposts' => -1));

foreach($books as $book)
{
    wp_delete_post( $book->ID, true);
}

//Method 2 -- Acces the database via SQL (use if confortable with SQL)
// video --> https://www.youtube.com/watch?v=FpnHvp9x48c&t=297s
//global $wpdb;
//$wpdb->query("DELETE FROM wp_post WHERE post_type = 'book' ");