<?php get_header(); ?>

   <div id="pagewrap" class="print">
   
      <?php if (have_posts()) while (have_posts()) : the_post(); ?>
      
         <div class="post clearfix">
            <h1>
               <?php $cats = get_the_category(); ?>
               <span class="class <?= $cats[0]->slug; ?>"><?= $cats[0]->name; ?></span>
               <?php the_title() ?>
            </h1>
            <div class="content"><?php the_content(); ?></div>
            <table><tbody><tr>
               <?php
                  $images_array = get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post->ID . '&orderby=date&order=DESC&posts_per_page=99');
                  $count = 0;
                  foreach ($images_array as $image)
                  {
                     $image_src = wp_get_attachment_image_src($image->ID, 'medium');
                     $image_url = $image_src[0];
                     echo '<td><img src="' . $image_url . '"/></td>';
                     $count++;
                     if ($count == 3) { echo '</tr><tr>'; $count = 0; }
                  }
                  if ($count == 1) { echo '<td></td><td></td>'; }
                  if ($count == 2) { echo '<td></td>'; }
               ?>
            </tr></tbody></table>
         </div>
      
      <?php endwhile; ?>
      
   </div>

<?php get_footer(); ?>