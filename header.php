<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

	<meta charset="<?php bloginfo('charset'); ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	
	<title><?php wp_title(''); ?></title>
	<meta name="title" content="<?php wp_title(''); ?>"/>
	<meta name="author" content="Zelen Communications"/>
	<meta name="copyright" content="Copyright <?php bloginfo('name'); ?> <?php echo date("Y"); ?>. All Rights Reserved."/>

	<?php if (is_search()) { ?><meta name="robots" content="noindex, nofollow"/><?php } ?>

   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
   
	<!-- wp_head start-->
   <?php wp_head(); ?>
	<!-- wp_head end-->
   
	<link rel="shortcut icon" type="text/css" href="<?= THEMEURL ?>/img/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?= THEMEURL ?>/style.css"/>
	<?php if (!isset($_GET['dev'])) : ?>
        <link rel="stylesheet" type="text/css" href="<?= THEMEURL ?>/theme-default.css"/>
        <link rel="stylesheet" type="text/css" href="<?= THEMEURL ?>/theme-alt-1.css"/>
        <!--<link rel="stylesheet" type="text/css" href="<?= THEMEURL ?>/theme-alt-2.css"/>-->
    <?php endif; ?>

   <script src="<?= THEMEURL ?>/js/functions.js"></script>
   <script>var ajaxurl = "<?= admin_url('admin-ajax.php') ?>";</script>
	
   <!-- Analytics -->
	
</head>

<body <?php body_class(); ?>>
	
   <?php
      global $linked_cats;
      $linked_cats = get_user_meta(get_current_user_id(), 'linked-classes', true);
      if (!$linked_cats) { $linked_cats = array(); }
      $dev_tag = (isset($_GET['dev'])) ? '?dev' : '';
   ?>
   
   <div id="header"><div>
   
      <input id="show-menu" type="checkbox">
      <ul id="phone-menu"><label for="show-menu">Show Menu</label><h1><?= (is_home()) ? (count($linked_cats) == 1) ? implode(',', $linked_cats) : 'All Classes' : wp_title(''); ?></h1></ul>
      <ul id="menu" class="menu clearfix">
      
         <?php if (count($linked_cats) == 1) : ?>
            <li><a href="<?= HOMEURL ?>/<?= $dev_tag ?>" class="menu-class <?= (is_home()) ? 'class="current"' : '' ?>"><?= implode(',', $linked_cats) ?></a>
         <?php else : ?>
            <li id="classes-top" <?= (is_category() || is_home()) ? (get_cat_name(get_query_var('cat')) != 'School') ? 'class="current"' : '' : '' ?>><a href="#" class="menu-class"><span class="desktop"><?= (is_category()) ? (get_cat_name(get_query_var('cat')) != 'School') ? get_cat_name(get_query_var('cat')) : 'All Classes' : 'All Classes' ?></span><span class="phone">Classes</span></a>
               <ul id="classes-dropdown">
                  <?php
                     foreach ($linked_cats as $index => $value)
                     {
                        $cat = get_category($index);
                        echo '<li><a href="' . HOMEURL . '/class/' . $cat->slug . '/' . $dev_tag . '" class="menu-class ' . $cat->slug . '">' . $value . '</a></li>';
                     }
                  ?>
                  <li><a href="<?= HOMEURL ?>/<?= $dev_tag ?>" class="menu-class all">All Classes</a></li>
               </ul>
            </li>
         <?php endif; ?>
         
         <li <?= (get_cat_name(get_query_var('cat')) == 'School') ? 'class="current"' : '' ?>><a href="<?= HOMEURL ?>/class/school/<?= $dev_tag ?>" class="menu-school">School</a></li>
         <li <?= (is_post_type_archive('download')) ? 'class="current"' : '' ?>><a href="<?= HOMEURL ?>/downloads/<?= $dev_tag ?>" class="menu-downloads">Downloads</a></li>
         <span>
             <li <?= ($post->post_title == 'Settings') ? 'class="current"' : '' ?>><a href="<?= HOMEURL ?>/settings/<?= $dev_tag ?>" class="menu-settings">Settings</a></li>
             <?php if (is_user_logged_in()) : ?><li><a href="<?= wp_logout_url(HOMEURL . '/' . ($dev_tag)); ?>" class="menu-logout">Logout</a></li><?php endif; ?>
         </span>
      </ul>
       
       <!-------------Google Analytics------------>
       
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-35697439-3', 'auto');
        ga('send', 'pageview');

    </script>
      
   </div></div>
