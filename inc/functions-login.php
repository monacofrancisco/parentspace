<?php
   // Force user login on the front end
   add_filter('template_include', 'login_template', 99);
   function login_template($template)
   {
      if (!is_user_logged_in()) 
      {
         $new_template = locate_template(array('page-login.php'));
         if ($new_template != '') { return $new_template ; }
      }
      return $template;
   }
   
   // Prevent going to wp-login on a failed login
   add_action('wp_login_failed', 'custom_login_failed');
   function custom_login_failed($username)
   { 
       $referrer = wp_get_referer();
       if ($referrer && !strstr($referrer, 'wp-login') && ! strstr($referrer,'wp-admin'))
       {
           wp_redirect(add_query_arg('login', 'failed', $referrer));
           exit;
       }
   }
   
   // Redirect non-admin to front end
   add_action('admin_init', 'redirect_authors');
   function redirect_authors()
   { 
      if (!current_user_can('manage_options') && 
         (strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') === false) &&
         (strpos($_SERVER['REQUEST_URI'], 'async-upload.php') === false))
      {
         wp_redirect(HOMEURL);
         die;
      }
   }
   
   // Allow logging in with email address instead of username
   add_filter('authenticate', 'allow_email_login', 20, 3);
   function allow_email_login($user, $username, $password)
   {
      if (is_email($username))
      {
         $user = get_user_by_email($username);
         if ($user) $username = $user->user_login;
      }
      return wp_authenticate_username_password(null, $username, $password);
   }
?>