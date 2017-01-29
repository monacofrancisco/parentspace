<?php

   // Add Downloads post type
   add_action('init', 'register_download_type');
   function register_download_type() 
   {
      $download_labels = array
      (
         'name' => 'Downloads',
         'singular_name' => 'Download',
         'menu_name' => 'Downloads',
         'name_admin_bar' => 'Download',
         'all_items' => 'All Downloads',
         'add_new' => 'Add New', 'Download',
         'add_new_item' => 'Add New Download',
         'edit_item' => 'Edit Download',
         'new_item' => 'New Download',
         'view_item' => 'View Download',
         'search_items' => 'Search Downloads',
         'not_found' =>  'No Downloads found.',
         'not_found_in_trash' => 'No Downloads found in Trash.', 
         'parent_item_colon' => 'Parent Download:',
      );
      register_post_type('download', array('public' => true, 'menu_position' => 6, 'labels' => $download_labels, 'supports' => array('title', 'editor', 'revisions', 'page-attributes'), 
                         'has_archive' => 'downloads', 'rewrite' => array('slug' => 'downloads', 'with_front' => FALSE)));
   }
   add_filter('post_updated_messages', 'downloads_updated_messages');
   function downloads_updated_messages( $messages )
   {
      global $post, $post_ID;
      $messages['download'] = array(
         0 => '', // Unused. Messages start at index 1.
         1 => sprintf( __('Download updated. <a href="%s">View Download</a>'), esc_url( get_permalink($post_ID) ) ),
         2 => __('Custom field updated.'),
         3 => __('Custom field deleted.'),
         4 => __('Download updated.'),
         /* translators: %s: date and time of the revision */
         5 => isset($_GET['revision']) ? sprintf( __('Download restored to revision from %s.'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
         6 => sprintf( __('Download published. <a href="%s">View Download</a>'), esc_url( get_permalink($post_ID) ) ),
         7 => __('Download saved.'),
         8 => sprintf( __('Download submitted. <a target="_blank" href="%s">Preview Download</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
         9 => sprintf( __('Download scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Download</a>'),
         // translators: Publish box date format, see http://php.net/date
         date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
         10 => sprintf( __('Download draft updated. <a target="_blank" href="%s">Preview Download</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
      );
      return $messages;
   }
   
   // Sort Downloads by menu_order
   add_filter('pre_get_posts', 'downloads_menu_order');
   function downloads_menu_order($wp_query)
   {
      $post_type = $wp_query->query['post_type'];
      if ($post_type == 'download')
      {
         $wp_query->set('posts_per_page', '999');
         $wp_query->set('orderby', 'menu_order');
         $wp_query->set('order', 'ASC');
      }
   }
   
   // Add File meta box to Downloads
   add_action('add_meta_boxes', 'download_meta_box');
   function download_meta_box()
   {
      add_meta_box('download-meta-box', 'File to Download', 'download_meta_box_output', 'download', 'normal', 'high');
   }
   
   function download_meta_box_output($post)
   {
      wp_nonce_field('download_meta_box_nonce', 'download_meta_box_nonce');
   ?>
      <p>
         <label for="download-file"><strong>File:</strong> </label>
         <input id="download-file" name="download-file" type="text" value="<?php echo get_post_meta($post->ID, 'download-file', true); ?>" size="50" readonly="readonly"/>
         <input id="choose-file" class="button" name="" type="button" value="Choose File"/>
      </p>
      <script>
         var file_frame;
         jQuery('#choose-file').on('click', function( event )
         {
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if (file_frame) { file_frame.open(); return; }
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media(
            {
               title: jQuery(this).data('uploader_title'),
               button: { text: jQuery(this).data('uploader_button_text'), },
               multiple: false // Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on('select', function()
            {
               attachment = file_frame.state().get('selection').first().toJSON();
               jQuery('#download-file').val(attachment.url);
            });
            // Finally, open the modal
            file_frame.open();
         });
      </script>
   <?php
   }
   
   // Save the Metabox values
   add_action('save_post', 'download_meta_box_save');
   function download_meta_box_save($post_id)
   {
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
      if (!isset($_POST['download_meta_box_nonce']) || !wp_verify_nonce( $_POST['download_meta_box_nonce'], 'download_meta_box_nonce' ) ) { return; }
      if (!current_user_can('edit_post')) { return; }
      if (isset($_POST['download-file'])) { update_post_meta($post_id, 'download-file', esc_attr($_POST['download-file'])); }
   }
   
?>