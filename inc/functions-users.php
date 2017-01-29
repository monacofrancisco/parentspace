<?php
   // Changes names of roles to Parent and Teacher
   add_action('init', 'change_role_name');
   function change_role_name()
   {
      global $wp_roles;
      if (!isset($wp_roles)) $wp_roles = new WP_Roles();
      $wp_roles->roles['editor']['name'] = 'Teacher';
      $wp_roles->role_names['editor'] = 'Teacher';           
      $wp_roles->roles['subscriber']['name'] = 'Parent';
      $wp_roles->role_names['subscriber'] = 'Parent';    
      // Remove other roles (only on theme activation)
      global $pagenow;
      if (is_admin() && isset($_GET['activated']) && $pagenow == "themes.php" )
      {
         remove_role('contributor');
         remove_role('author');
      }
   }
   
   // Clean up New User page
   add_action('admin_footer-user-new.php', 'cleanup_user_new');
   function cleanup_user_new()
   {
      echo
      '
         <script>
            (function($)
            {
               $("label:contains(\'Website\')").parent().parent().hide(0);
            })(jQuery)
         </script>
      ';
   }
   
   // Clean up Edit User and Profile pages
   add_action('admin_footer-user-edit.php', 'cleanup_user_edit');
   add_action('admin_footer-profile.php', 'cleanup_user_edit');
   function cleanup_user_edit()
   {
      echo
      '
         <script>
            (function($)
            {
               $parent = $("h3:contains(\'Personal Options\')");
               $parent.next().hide(0);
               $parent.hide(0);
               $("label[for=nickname]").parent().parent().hide(0);
               $("label[for=display_name]").parent().parent().hide(0);
               $("label[for=url]").parent().parent().hide(0);
               $("label[for=description]").parent().parent().hide(0);
               $("h3:contains(\'Name\')").hide(0);
               $("h3:contains(\'Contact Info\')").hide(0);
               $("h3:contains(\'About the user\')").hide(0);
               $("h3:contains(\'About Yourself\')").hide(0);
            })(jQuery)
         </script>
      ';
   }
   
   // Insert Classes list into New User, Edit User, and Profile pages
   add_action('admin_footer-user-new.php', 'insert_user_classes');
   add_action('admin_footer-user-edit.php', 'insert_user_classes');
   add_action('admin_footer-profile.php', 'insert_user_classes');
   function insert_user_classes()
   {
      $cats = get_categories('hide_empty=0&exclude=1');
      echo
      '
         <script>
            (function($)
            {
               $("p.submit").before("<h3>Linked Classes</h3><p>Please check all classes to be linked to this user.</p>");
               $("p.submit").before("<table id=\'classes\' class=\'form-table\'><tbody>");
      ';
      foreach ($cats as $cat)
      { 
         echo '$("#classes tbody").append(\'<tr><th><label for="cats[' . $cat->term_id . ']">' . esc_attr($cat->name) . '</label></th><td><input id="cats[' . $cat->term_id . ']" name="cats[' . $cat->term_id . ']" type="checkbox" value="' . esc_attr($cat->name) . '"></td></tr>\');' . "\n";
      }
      echo
      '
               $("p.submit").before("</tbody></table>");
            })(jQuery)
         </script>
         <style>.form-table th, .form-table td { padding-top: 10px; padding-bottom: 10px; } #classes { margin-bottom: 40px; } #classes th, #classes td { padding-top: 5px; padding-bottom: 5px; }</style>
      ';
   }
   
   // Insert/Update linked classes after saving a user or profile
   add_action('user_register', 'update_linked_classes');
   add_action('edit_user_profile_update', 'update_linked_classes');
   add_action('personal_options_update', 'update_linked_classes');
   function update_linked_classes($user_id)
   {
      if (current_user_can('edit_user', $user_id))
      {
         update_user_meta($user_id, 'linked-classes', $_POST['cats']);
      }
   }
   
   // Check linked classes in Edit User and Profile pages
   add_action('admin_footer-user-edit.php', 'check_user_classes');
   add_action('admin_footer-profile.php', 'check_user_classes');
   function check_user_classes()
   {
      if (isset($_GET['user_id']) && $_GET['user_id'] > 0) { $linked_cats = get_user_meta($_GET['user_id'], 'linked-classes', true); }
      else { $linked_cats = get_user_meta(get_current_user_id(), 'linked-classes', true); }
      echo
      '
         <script>
            (function($)
            {
      ';
      foreach ($linked_cats as $index => $value)
      { 
         echo '$("input#cats\\\\[' . $index . '\\\\]").prop("checked", true);' . "\n";
      }
      echo
      '
            })(jQuery)
         </script>
      ';
   }
?>