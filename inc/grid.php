<div class="loading"></div>
<?php
   global $cat_name, $thumb;
   if (isset($_POST['postid'])) { $post_id = $_POST['postid']; }
   else { $post_id = $post->ID; }
   $images_array = get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post_id . '&orderby=date&order=DESC&posts_per_page=99');
   $image_ids = array(); 
   foreach ($images_array as $image)
   {
      $thumb++;
      $image_src = wp_get_attachment_image_src($image->ID, 'thumbnail');
      $image_url = $image_src[0];
      $target_src = wp_get_attachment_image_src($image->ID, 'large');
      $target_url = $target_src[0];
      echo '<img id="thumb-' . $thumb . '" class="thumb" thumb="' . $thumb . '" src="' . $image_url . '" large="' . $target_url . '"/>';
      $image_ids[] = $image->ID;
   }
   // Teacher photo upload 
   if ((current_user_can('edit_posts') && $cat_name != 'School') || current_user_can('install_plugins'))
   {
      echo '<div class="upload" grid="' . $post_id . '"><span>Upload Photos</span></div>';
   }
?>
