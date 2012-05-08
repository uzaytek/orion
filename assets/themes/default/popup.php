<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="<?php theme_description(); ?>" />
<meta name="keywords" content="<?php theme_keywords(); ?>" />
<title><?php theme_title(); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php theme_url();?>style.css" />
<style>
   body {
   background-color:white;
   background-image:none;
 }
</style>
</head>
<body <?php if(isset($onloadjs)) {echo $onloadjs;} ?>>
   <?php echo isset($output) ? $output : _('Output not found');?>
</body>
</html>