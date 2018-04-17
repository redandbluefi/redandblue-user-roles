<?php
/**
 * Plugin Name: redandblue user roles
 * Plugin URI: https://github.com/redandbluefi/redandblue-user-roles
 * Description: A Lightweight WordPress plugin that adds a Content Manager role to wp-admin.
 * Version: 0.1.0
 * Author: Red & Blue Oy, Jari Savolainen
 * Author URI: https://www.redandblue.fi
 * Requires at least: 4.4.2
 * Tested up to: 4.9
 *
 * Text Domain: redandblue-user-roles
 * Domain Path: /languages
*/

if( !defined( 'ABSPATH' )  )
  exit();

register_activation_hook( __FILE__, 
  function() {
    // remove_role('redandblue_content_manager'); // erase after done
    /*
    $settings = [
      'enable_comments' => apply_filters('redandblue-user-roles/rnb_urc_comments', false),
    ];*/
    $caps = 
      [
        'read' => true,
        
        // Posts
        'edit_posts' => true,
        'publish_posts' => true,
        'edit_others_posts' => true,
        'edit_published_posts'=> true,
        'read_private_posts' => false,
        'manage_categories' => true,
        
        // Pages
        'edit_pages' => true,
        'edit_others_pages' => true,
        'publish_pages' => true,
        'edit_published_pages' => true,
        'read_private_pages' => false,

        // Media
        'upload_files' => true,
        
        // Customize theme
        'edit_theme_options' => true,
        
        // plugins & themes
        'install_plugins' => false,
        'install_themes' => false,
        'upload_plugins' => false,
        'upload_themes' => false,
        'activate_plugins' => false,
        'delete_themes' => false,
        'delete_plugins' => false,
        'edit_plugins' => false,
        
        // Users
        'create_users' => false,
        'delete_users' => false, 
        
        // Comments
        // 'moderate_comments' => false,
        // 'edit_comment' => false,

        // Options and other
        'manage_links' => true,
        'edit_dashboard' => true,
        'update_core' => false,
        'update_plugins' => false,
        'update_themes' => false,
        'level_10' => true,

      ];
    add_role( 'redandblue_content_manager', __('Content Manager', 'redandblue'), $caps );
  }
);