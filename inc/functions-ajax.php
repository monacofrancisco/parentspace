<?php
   // Send next page of posts by ajax
   add_action('wp_ajax_next_page', 'ajax_next_page');
   add_action('wp_ajax_nopriv_next_page', 'ajax_next_page');
   function ajax_next_page()
   {
      get_template_part('inc/posts');
      die;
   }
   
   // Edit a post by ajax
   add_action('wp_ajax_insert_post', 'ajax_insert_post');
   add_action('wp_ajax_nopriv_insert_post', 'ajax_insert_post');
   function ajax_insert_post()
   {
      if (isset($_POST['del']) && ($_POST['del'] == 1))
      {
         wp_delete_post($_POST['id']);
         echo 'DELETED'; 
         die;
      }
      $data = array
      (
        'post_title' => $_POST['title'],
        'post_content' => $_POST['content'],
        'post_status' => 'publish'
      );
      if (isset($_POST['id']) && ($_POST['id'] != 'NEW'))
      { 
         $data['ID'] = $_POST['id'];
         echo wp_update_post($data);
      }
      elseif (isset($_POST['clase']) && ($_POST['clase'] > 0))
      {
         $data['post_category'] = array($_POST['clase']);
         echo wp_insert_post($data);
      }
      die;
   }
   
   // Queue media uploader scripts
   add_action('wp_enqueue_scripts', 'add_media_upload_scripts');
   function add_media_upload_scripts()
   {
      if (!is_admin() && (is_home() || is_archive())) { wp_enqueue_media(); }
   }
   
   // Fetch new grid by ajax after uploading
   add_action('wp_ajax_fetch_grid', 'ajax_fetch_grid');
   add_action('wp_ajax_nopriv_fetch_grid', 'ajax_fetch_grid');
   function ajax_fetch_grid()
   {
      $post = get_post($_POST['postid']);
      setup_postdata($post);
      get_template_part('inc/grid');
      die;
   }
?>