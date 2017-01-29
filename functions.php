<?php

   // Register theme directory url and path constants
   define("HOMEURL", get_bloginfo('url'));
   define("HOMEPATH", ABSPATH);
   define("THEMEURL", get_stylesheet_directory_uri());
   define("THEMEPATH", TEMPLATEPATH);
   
	// Clean up the <head>
   add_action('init', 'removeHeadLinks');
	function removeHeadLinks()
   {
      remove_action('wp_head', 'wp_generator');
      remove_action('wp_head', 'feed_links', 2);
      remove_action('wp_head', 'feed_links_extra', 3);
      remove_action('wp_head', 'rsd_link');
      remove_action('wp_head', 'wlwmanifest_link');
      remove_action('wp_head', 'index_rel_link');
      remove_action('wp_head', 'parent_post_rel_link', 10, 0);
      remove_action('wp_head', 'start_post_rel_link', 10, 0);
      remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
      remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
      remove_action('wp_head', 'noindex', 1);
   }
	
   // Get jQuery queued
   add_action('wp_enqueue_scripts', 'add_jquery');
   function add_jquery() { wp_enqueue_script('jquery'); }
   
   // Add editor CSS styles through editor-style.css
   add_editor_style();
   
   // Add PDF as a MIME type in Media Library
   add_filter('post_mime_types', 'modify_post_mime_types');
   function modify_post_mime_types($post_mime_types)
   {
      $post_mime_types['application/pdf'] = array(__('PDF'), __('Manage PDF'), _n_noop('PDF <span class="count">(%s)</span>', 'PDF <span class="count">(%s)</span>'));
      return $post_mime_types;
   }
   
   include('inc/functions-login.php');
   include('inc/functions-admin.php');
   include('inc/functions-journals.php');
   include('inc/functions-downloads.php');
   include('inc/functions-users.php');
   include('inc/functions-ajax.php');
   
?>