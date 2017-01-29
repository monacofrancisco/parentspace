<?php get_header(); ?>

	<div id="pagewrap" class="downloads">
   
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
         <a target="_blank" href="<?= get_post_meta($post->ID, 'download-file', true); ?>">
            <div class="post" id="post-<?php the_ID(); ?>">
               <h1><?php the_title(); ?></h1>
               <div class="content"><?php the_content(); ?></div>
            </div>
         </a>

      <?php endwhile; endif; ?>
      
   </div>

<?php get_footer(); ?>
