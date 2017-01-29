<?php get_header(); ?>

   <div class="post login" id="post-<?php the_ID(); ?>">
      <form method="post" action="<?= HOMEURL ?>/wp-login.php" id="loginform" name="loginform">
         <img class="logo" src="<?= THEMEURL ?>/img/logo.png"/>
         <?php if ($_GET['login'] == 'failed') : ?>
            <p class="error">Sorry, your login info is incorrect.</p>
            <p class="error">If you forgot your password, <a href="<?= HOMEURL ?>/wp-login.php?action=lostpassword">click here</a>.</p>
         <?php endif; ?>
			<p class="login-username">
				<label for="user_login">Username</label>
            <input type="text" size="20" value="" class="input" id="user_login" name="log" placeholder="Email Address">
            <span class="bg"></span>
			</p>
			<p class="login-password">
				<label for="user_pass">Password</label>
            <input type="password" size="20" value="" class="input" id="user_pass" name="pwd" placeholder="Password">
            <span class="bg"></span>
			</p>
			<p class="login-remember"><input type="checkbox" value="forever" id="rememberme" name="rememberme"><label for="rememberme" class="custom-check"></label><label for="rememberme">Remember Me</label></p>
			<p class="login-forgot"><a href="<?= wp_lostpassword_url(HOMEURL); ?>">Forgot your password?</a></p>
			<p class="login-submit">
				<input type="submit" value="Log In" class="button-primary" id="wp-submit" name="wp-submit">
				<input type="hidden" value="<?= HOMEURL . '/' . ((isset($_GET['dev'])) ? '?dev' : '') ?>" name="redirect_to">
			</p>
		</form>
   </div>
   
   <script>
      (function($)
      {
         $('#user_login').attr('placeholder', 'Email Address');
         $('#user_pass').attr('placeholder', 'Password');
         $('#loginform').submit(function()
         {
            if ($('#user_login').val().trim() == '') { return false; }
            if ($('#user_pass').val().trim() == '') { return false; }
         });
      })(jQuery)
   </script>

<?php get_footer(); ?>
