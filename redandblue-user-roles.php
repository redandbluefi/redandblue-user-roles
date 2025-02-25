<?php
/**
 * Plugin Name: redandblue user roles
 * Plugin URI: https://github.com/redandbluefi/redandblue-user-roles
 * Description: A Lightweight WordPress plugin that adds a Content Manager role to wp-admin.
 * Version: 0.1.2
 * Author: Red & Blue Oy, Jari Savolainen
 * Author URI: https://www.redandblue.fi
 * Requires at least: 4.4.2
 * Tested up to: 4.9
 *
 * Text Domain: redandblue-user-roles
 * Domain Path: /languages
*/

if( !defined( 'ABSPATH' ) ) exit();

// Return role capabilities
function redandblue_capabilities(){

  $edit_users = apply_filters('redandblue-user-roles/rnb_urc_users', false);
  $edit_comments = apply_filters('redandblue-user-roles/rnb_urc_comments', false);
  $caps_filter = apply_filters('redandblue-user-roles/rnb_urc_caps', []); // caps can be over written by this filter

  $caps =
    [
      'read' => true,

      // Posts
      'edit_posts' => true,
      'delete_posts' => true,
      'delete_others_posts' => true,
      'delete_published_posts' => true,
      'publish_posts' => true,
      'edit_others_posts' => true,
      'edit_published_posts'=> true,
      'read_private_posts' => false,
      'manage_categories' => true,

      // Pages
      'edit_pages' => true,
      'delete_pages' => true,
      'delete_others_pages' => true,
      'delete_published_pages' => true,
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
      'edit_users' => $edit_users,
      'list_users' => $edit_users,
      'delete_users' => $edit_users,
      'create_users' => $edit_users,
      'delete_users' => $edit_users,
      'promote_users' => $edit_users,

      // Comments
      'moderate_comments' => $edit_comments,
      'edit_comment' => $edit_comments,

      // Options and other
      'manage_options' => true,
      'manage_links' => true,
      'edit_dashboard' => true,
      'update_core' => false,
      'update_plugins' => false,
      'update_themes' => false,
      'level_10' => true,
      'manage_downloads' => true,
  ];
  if (!empty($caps_filter)) {
    $caps = array_merge($caps, $caps_filter);
  }
  return $caps;
}

function refresh_redandblue_role(){
  remove_role('redandblue_content_manager');
  add_role( 'redandblue_content_manager', __('Content Manager', 'redandblue'), redandblue_capabilities() );
}

// initial registration of the role
register_activation_hook( __FILE__, 'refresh_redandblue_role');

/* 'promote_users' => true, allows user to add any role
 * including administrator. This just simply removes administrator role 
 * from the select list in the edit and create forms
 */
add_filter('editable_roles', function ($roles){
  if ( !current_user_can('manage_network') && // This allows network admins to add administrators
       current_user_can('redandblue_content_manager') ) {
    $roles = array_filter($roles, function($k){
      return $k != 'administrator';
    },ARRAY_FILTER_USE_KEY);
  }
  return array_reverse($roles, true);
});

// Hide administrators from content managers
add_action('pre_user_query', function ($user_search) {
  $user = wp_get_current_user();
  if ( !current_user_can('manage_network') && // This allows network admins to see all users
       current_user_can('redandblue_content_manager') ) {
    global $wpdb;
    $user_search->query_where = 
    str_replace('WHERE 1=1', 
      "WHERE 1=1 AND {$wpdb->users}.ID IN (
      SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
      WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
      AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')", 
      $user_search->query_where
    );
  }
});

// CLI command to refresh roles
if ( class_exists( 'WP_CLI' ) ) {
  WP_CLI::add_command( 'rnb-refresh-roles', function(){
    refresh_redandblue_role();
    WP_CLI::success( 'Roles refreshed' );
  });
}

// remove unnessecary menu items from content managers
add_action('admin_menu',
  function(){
    $user = wp_get_current_user();
    if( in_array('redandblue_content_manager', $user->roles) ) {
      remove_menu_page('tools.php');
      remove_menu_page('themes.php');
      remove_menu_page('options-general.php');
      remove_menu_page('plugins.php');
      remove_menu_page('edit.php?post_type=acf-field-group');

      if (!apply_filters('redandblue-user-roles/rnb_urc_users', false)) {
        remove_menu_page('users.php');
      }

      if (!apply_filters('redandblue-user-roles/rnb_urc_comments', false)) {
        remove_menu_page('edit-comments.php');
      }
      // Change position of wp-menus
      add_menu_page( __('Valikot', 'redandblue'), __('Valikot', 'redandblue'), 'manage_options', 'nav-menus.php','', 'dashicons-editor-justify', 20);
    }
  }
);
