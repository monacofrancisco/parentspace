<?php 
   if (isset($_POST['submit']) && isset($_POST['id']) && current_user_can('edit_user', $_POST['id']))
   {
      $data = array
      (
         'ID' => $_POST['id'],
         'user_email' => $_POST['email'],
         'user_pass' => $_POST['password']
      );
      if ($_POST['password'] != $_POST['password-repeat']) { $pw_match_error = true; }
      elseif (strlen($_POST['password']) < 8) { $pw_length_error = true; }
      else { if (wp_update_user($data) == $_POST['id']) { $updated = true; } }
   }
   
   get_header();
?>

	<div id="pagewrap" class="settings">
   
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
         <div class="post" id="post-<?php the_ID(); ?>">
            <!--<h1><?php the_title(); ?></h1>-->
            <div class="content">
               <?php the_content(); ?>
               <?php if ($pw_match_error) : ?><div class="updated fail">Sorry, your password fields did not match, please try again.</div><?php endif; ?>
               <?php if ($pw_length_error) : ?><div class="updated fail">Please choose a password of at least 8 characters.</div><?php endif; ?>
               <?php if ($updated) : ?><div class="updated success">Your profile has been updated successfully.</div><?php endif; ?>
               <?php $data = get_userdata(get_current_user_id()); ?>
               <form id="settings"action="." method="post">
                   
                  <div class="settings-parent">
                      
                      <h1>Details</h1>
                      
                      <!--<div class="settings-item">
                          <?php if (current_user_can('edit_posts')) : ?>
                             <label for="role">Account Type</label>
                             <input id="role" name="role" type="text" value="<?= ucwords($data->roles[0]) ?>" disabled="disabled"/>
                          <?php endif; ?>
                          </div>-->
                      
                      <div class="settings-item">
                          <label for="fname">Name</label>
                          <input id="fname" name="fname" type="text" value="<?= $data->first_name ?> <?= $data->last_name ?>" disabled="disabled"/>
                      </div>

                      <div class="settings-item">
                          <label for="email">Email</label>
                          <input id="email" name="email" type="email" value="<?= $data->user_email ?>"/>
                      </div>

                      <div class="settings-item">
                          <label for="password">Change Password</label>
                          <input id="password" name="password" type="password" value=""/>
                          <div class="limit-line"></div>
                          <label for="password-repeat">Repeat Password</label>
                          <input id="password-repeat" name="password-repeat" type="password" value=""/>
                      </div>
                  </div>
                  
                  <div class="settings-parent">
                      
                      <h1>Privileges</h1>
                      
                      <div class="settings-item">
                          <label>Classes you can see</label>
                          <table id="classes"><tbody>
                             <?php
                                $cats = get_categories('hide_empty=0&exclude=1');
                                $linked_cats = get_user_meta(get_current_user_id(), 'linked-classes', true);
                                $linked_keys = array_keys($linked_cats);
                                foreach ($cats as $cat)
                                {
                                   $checked = (in_array($cat->term_id, $linked_keys)) ? 'checked="checked"' : '';
                                   $unchecked_class = (!in_array($cat->term_id, $linked_keys)) ? 'class="unchecked"' : '';
                                   echo '<tr ' . $unchecked_class . '><th><label for="cats[' . $cat->term_id . ']">' . esc_attr($cat->name) . '</label></th><td><input id="cats[' . $cat->term_id . ']" name="cats[' . $cat->term_id . ']" type="checkbox" value="' . esc_attr($cat->name) . '" ' . $checked . ' disabled="disabled"></td></tr>';
                                }
                             ?>
                          </tbody></table>
                      </div>
                  </div>
                   
                  <input id="id" name="id" type="hidden" value="<?= get_current_user_id() ?>"/>
                  <input id="submit" name="submit" type="submit" value="Submit"/>
               </form>
            </div>
         </div>

      <?php endwhile; endif; ?>
      
   </div>

<?php get_footer(); ?>
