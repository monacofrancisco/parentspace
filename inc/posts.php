<?php
   global $linked_cats, $cat_name, $thumb;
   // Find proper category from category page, ajax params, or user
   if (is_category()) { $user_cats = get_query_var('cat'); }
   elseif (isset($_POST['clase'])) { $user_cats = $_POST['clase']; }
   else { $user_cats = implode(',', array_keys($linked_cats)); }
   if ($user_cats == '') { $user_cats = '99999'; }
   $cat_name = get_cat_name($user_cats);
   // Get offsets from ajax params or begin with new offsets
   $page = (isset($_POST['page'])) ? ++$_POST['page'] : 1;
   $offset = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
   $thumb = (isset($_POST['thumb'])) ? $_POST['thumb'] : 0;
   // Loop
   $posts = get_posts('post_type=post&posts_per_page=' . get_option('posts_per_page') . '&cat=' . $user_cats . '&offset=' . + $offset);
   if (count($posts)) :
?>
   <div id="page-<?= $page ?>" page="<?= $page ?>">
      <?php foreach ($posts as $post) : setup_postdata($post); $offset++; ?>
         <div class="post clearfix" id="post-<?= $offset ?>" offset="<?= $offset ?>">
            <h1 class="clearfix">
               <?php $cats = get_the_category(); ?>
               <span class="class <?= $cats[0]->slug; ?>"><?= $cats[0]->name; ?></span>
               <?= $post->post_title ?>
               <div class="right-buttons">
                  <span class="print-button"><a href="<?= get_permalink() ?>">Print</a></span>
                  <?php if ((current_user_can('edit_posts') && $cat_name != 'School') || current_user_can('install_plugins')) : ?><span class="edit-button">Edit</span><?php endif; ?>
               </div>
            </h1>
            <div class="content"><?php the_content(); ?></div>
            <?php if ((current_user_can('edit_posts') && $cat_name != 'School') || current_user_can('install_plugins')) : ?>
               <!-- Teacher content editor -->
               <form class="edit-content" method="post" action=".">
                  <textarea name="content"><?= $post->post_content ?></textarea>
                  <input name="title" type="hidden" value="<?= $post->post_title ?>"/>
                  <input name="id" type="hidden" value="<?= $post->ID ?>"/>
                  <input name="submit" type="submit" value="Save"/>
                  <input name="delete" type="submit" value="Delete"/>
               </form>
               <div class="loading"><img src="<?= THEMEURL ?>/img/loading.gif"/></div>
            <?php endif; ?>
            <div class="grid" id="grid-<?= $post->ID ?>">
               <?php get_template_part('inc/grid'); ?>
            </div>
         </div>
      <?php endforeach; ?>
   </div>
<?php endif; ?>