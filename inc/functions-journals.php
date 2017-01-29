<?php

   // Replace Posts label as Journals in Admin Panel 
   add_action( 'admin_menu', 'change_post_menu_label' );
   function change_post_menu_label()
   {
      global $menu;
      global $submenu;
      $menu[5][0] = 'Journals';
      $submenu['edit.php'][5][0] = 'Journals';
      $submenu['edit.php'][10][0] = 'Add Journals';
      echo '';
   }
   add_action( 'init', 'change_post_object_label' );
   function change_post_object_label()
   {
      global $wp_post_types;
      $labels = &$wp_post_types['post']->labels;
      $labels->name = 'Journals';
      $labels->singular_name = 'Journal';
      $labels->menu_name = 'Journals';
      $labels->name_admin_bar = 'Journal';
      $labels->all_items = 'All Journals';
      $labels->add_new = 'Add Journal';
      $labels->add_new_item = 'Add Journal';
      $labels->edit_item = 'Edit Journal';
      $labels->new_item = 'Journal';
      $labels->view_item = 'View Journal';
      $labels->search_items = 'Search Journals';
      $labels->not_found = 'No Journals found.';
      $labels->not_found_in_trash = 'No Journals found in Trash.';
      $labels->parent_item_colon = 'Parent Journal:';
   }
   add_filter('post_updated_messages', 'journals_updated_messages');
   function journals_updated_messages($messages)
   {
      global $post, $post_ID;
      $messages['post'] = array(
         0 => '', // Unused. Messages start at index 1.
         1 => sprintf( __('Journal updated. <a href="%s">View Journal</a>'), esc_url( get_permalink($post_ID) ) ),
         2 => __('Custom field updated.'),
         3 => __('Custom field deleted.'),
         4 => __('Journal updated.'),
         /* translators: %s: date and time of the revision */
         5 => isset($_GET['revision']) ? sprintf( __('Journal restored to revision from %s.'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
         6 => sprintf( __('Journal published. <a href="%s">View Journal</a>'), esc_url( get_permalink($post_ID) ) ),
         7 => __('Journal saved.'),
         8 => sprintf( __('Journal submitted. <a target="_blank" href="%s">Preview Journal</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
         9 => sprintf( __('Journal scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Journal</a>'),
         // translators: Publish box date format, see http://php.net/date
         date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
         10 => sprintf( __('Journal draft updated. <a target="_blank" href="%s">Preview Journal</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
      );
      return $messages;
   }

   // Change name of Categories taxonomy to Classes
   add_action('init', 'cat_classes');
   function cat_classes()
   {
      global $wp_taxonomies;
      $wp_taxonomies['category']->labels = (object)array
      (
         'name' => 'Classes',
         'singular_name' => 'Class',
         'search_items' => 'Search Classes',
         'popular_items' => NULL,
         'all_items' => 'All Classes',
         'parent_item' => 'Parent Class',
         'parent_item_colon' => 'Parent Class:',
         'edit_item' => 'Edit Class',
         'view_item' => 'View Class',
         'update_item' => 'Update Class',
         'add_new_item' => 'Add New Class',
         'new_item_name' => 'New Class Name',
         'separate_items_with_commas' => NULL,
         'add_or_remove_items' => NULL,
         'choose_from_most_used' => NULL,
         'not_found' => NULL,
         'menu_name' => 'Classes',
         'name_admin_bar' => 'class'
      );
      $wp_taxonomies['category']->label = 'Classes';
   }
   
   // Change name in category dropdown in edit.php
   add_action('admin_footer-edit.php', 'classes_dropdown');
   function classes_dropdown() { echo "<script>jQuery('select#cat option:first-child').html('All classes');</script>"; }
   
?>