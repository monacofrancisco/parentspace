<?php

   // Delete admin bar from front end for non-admin
   if (!current_user_can('install_plugins')) { add_filter('show_admin_bar', '__return_false'); }

   // Redirect to Posts list on login
   if (preg_match('#wp-admin/(index.php|\?\w+)?$#',$_SERVER['REQUEST_URI'])) { wp_redirect(get_option('siteurl') . '/wp-admin/edit.php'); }

   // Insert custom CSS into admin bar
   add_action('admin_bar_menu', 'custom_admin_bar_css');
   function custom_admin_bar_css()
   {
      echo '<style>';
      echo "li#wp-admin-bar-wp-logo { display: none !important; }\n";
      echo "li#wp-admin-bar-site-name div { display: none !important; }\n";
      echo "li#wp-admin-bar-comments { display: none !important; }\n";
      //echo "li#wp-admin-bar-new-post { display: none !important; }\n";
      echo "li#wp-admin-bar-new-media { display: none !important; }\n";
      echo "li#wp-admin-bar-new-page { display: none !important; }\n";
      echo "li#wp-admin-bar-edit { display: none !important; }\n";
      echo "li#wp-admin-bar-search { display: none !important; }\n";
      echo "li#wp-admin-bar-wpseo-menu { display: none !important; }\n";
      echo "li#wp-admin-bar-itsec_admin_bar_menu { display: none !important; }\n";
      echo '</style>';
   }
   
   // Insert custom CSS into login page
   add_action('login_head', 'custom_login_css');
   function custom_login_css()
   {
      echo '<style>';
      echo "body.login #login h1 a { visibility: hidden; }\n";
      echo '</style>';
   }
   
   // Insert custom CSS into admin pages
   add_action('admin_head', 'custom_admin_css');
   function custom_admin_css()
   {
      echo '<style>';
      echo "#menu-dashboard, #menu-dashboard+li { display: none; }\n";
      //echo "#menu-posts { display: none; }\n";
      echo "#menu-posts ul li:last-child { display: none; }\n";
      echo "#menu-pages { display: none; }\n";
      echo "#menu-comments { display: none; }\n";
      echo "#menu-appearance { display: none; }\n";
      echo "#menu-plugins { display: none; }\n";
      echo "#menu-tools { display: none; }\n";
      if (!current_user_can('edit_pages')) { echo "#menu-media { display: none; }\n"; }
      echo "#contextual-help-link-wrap { display: none; }\n";
      echo ".update-nag { display: none; }\n";
      echo "#wpfooter { display: none; }\n";
      echo '</style>';
      //echo '<script>jQuery(function(){ jQuery("#adminmenu").prepend(jQuery("#menu-pages")); });</script>';
   }   
?>