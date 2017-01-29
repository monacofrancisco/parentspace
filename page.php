<?php get_header(); ?>

	<div id="pagewrap">
   
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
         <div class="post" id="post-<?php the_ID(); ?>">
            <h1><?php the_title(); ?></h1>
            <div class="content"><?php the_content(); ?></div>
         </div>

      <?php endwhile; endif; ?>
      
   </div>

<?php get_footer(); ?>
