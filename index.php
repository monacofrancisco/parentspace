<?php get_header(); ?>

   <?php
      global $linked_cats;
      $cat_name = single_cat_title('', false);
   ?>

	<div id="pagewrap" clase="<?= (is_category()) ? get_query_var('cat') : implode(',', array_keys($linked_cats)) ?>">

      <div id="preloader"></div>
      <div id="spotlight"><div class="close">X</div><span></span><img id="spotlight-img" thumb="" src=""/></div>
      
      <?php if ((current_user_can('edit_posts') && $cat_name != 'School') || current_user_can('install_plugins')) : ?>
         <div id="addnew" class="post">Add New</div>
         <div id="newpost" class="post clearfix">
         
            <form id="addnew-form" class="edit-content" method="post" action=".">
               <span class="close">X</span>
               <input name="title" type="text" placeholder="Title or Date of the Day" value="<?= date('D - M j') ?>"/>
               <textarea name="content" placeholder="Message of the Day"></textarea>
               
               <input name="id" type="hidden" value="NEW"/>
               <input name="delete" type="submit" value="Delete"/>
               <input name="submit" type="submit" value="Submit"/>
                
               <?php if ($cat_name == 'School') : ?>
                  <select name="clase" disabled="disabled">
                     <option value="<?= get_query_var('cat') ?>"><?= $cat_name ?></option>
                  </select>
               <?php else : ?>
                  <?php $select_disable = (count($linked_cats) <= 1) ? 'disabled="disabled"' : ''; ?>
                  <select name="clase" <?= $select_disable ?>>
                     <?php if (count($linked_cats) == 0) : ?>
                        <option value="-">ERROR: No Class Found</option>
                     <?php else : ?>
                        <?php foreach ($linked_cats as $index => $value) { echo '<option value="' . $index . '">' . $value . '</option>';} ?>
                     <?php endif; ?>
                  </select>
               <?php endif; ?>
                
            </form>
            
            <div class="loading"><img src="<?= THEMEURL ?>/img/loading.gif"/></div>
         </div>
      <?php endif; ?>

      <?php get_template_part('inc/posts'); ?>

	</div>
   
   <div id="scroller"><div><img src="<?= THEMEURL ?>/img/loading.gif"/></div></div>

<?php get_footer(); ?>
