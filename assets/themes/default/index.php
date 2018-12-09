<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="<?php theme_description(); ?>" />
<meta name="keywords" content="<?php theme_keywords(); ?>" />
<meta name="generator" content="<?php meta_generator() ?>" />
<title><?php theme_title(); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php theme_url();?>style.css" />
<script type="text/javascript">
<!--
   SITE_ADDRESS = '<?php echo LC_SITE;?>';
//-->
</script>
<script type="text/javascript" src="<?php theme_site_url();?>assets/javascripts.js?v=0.0.1"></script>
<script type="text/javascript" src="<?php theme_site_url();?>assets/prototype.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php theme_site_url();?>assets/jqFancyTransitions.1.8.min.js"></script>
<script type="text/javascript">
<!--
	$(document).ready( function(){
	    $('#slideshowHolder').jqFancyTransitions({ width: 480, height: 300 });
	});
//-->
</script>
</head>
<body>
<div id="container">
	<div id="header">
      <p id="logo"><?php theme_logo();?></p>
		<h1><?php theme_sitename();?></h1>
      <?php theme_banner('top'); ?>
      <p id="slogan"><?php theme_slogan();?></p>
	</div>
   <hr size=1 style="color:#dbdbdb;width:963px;margin:0 auto;padding-top:5px;">
	<div id="topnav">
   <?php theme_menu();?>
	</div>
	<div id="content-container">
		<div id="left-boxes">
   <div id="cat-box"><?php theme_category_box(); ?></div>
   <div id="login-box"><?php theme_member_login_box(); ?></div>
   <div id="search-box"><?php theme_search_box(); ?></div>
   <div id="bestseller-box"><?php theme_bestseller_box(); ?></div>
   <?php theme_gallery_box();?>
   <?php theme_banner('left'); ?>
   <p><img src="<?php theme_url();?>images/paypalvisa.png" border="0" alt="Paypal Visa MasterCard" /></p>
		</div>
		<div id="content">
   <?php 
echo ON_Say::get();
theme_content($output);
   ?>
		</div>
		<div id="aside">
   <div id="news-box"><?php theme_news_box();?></div>
   <?php theme_banner('right'); ?>
   <div id="bulletin-box"><?php theme_bulletin_box();?></div>
		</div>
		<div id="footer">
		<?php theme_footer_menu();?> 
   <p>Template VirtueMart, <?php echo meta_generator(); ?></p>
   <p><?php theme_address();?></p>
		</div>
	</div>
</div>
</body>
</html>