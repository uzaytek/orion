<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title><?php echo _('Admin Area'); ?></title>
<link rel="stylesheet" type="text/css" href="./css/theme.css" />
<link rel="stylesheet" type="text/css" href="./css/style.css" />
<link rel="stylesheet" type="text/css" href="./css/me.css" />
<script type="text/javascript">
<!--
   if(document.cookie.charAt(6) > 0) {
     var StyleFile = "theme" +  document.cookie.charAt(6)    + ".css";
     document.writeln('<link rel="stylesheet" type="text/css" href="css/' + StyleFile + '">');
   }
//-->
</script>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="./css/ie.css" />
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="./css/ie6.css" />
<![endif]-->
<script language="javascript" type="text/javascript" src="./js/javascripts.js"></script>
<script language="javascript" type="text/javascript" src="./js/ADxMenu.js"></script>
</head>
<body class="adxmenu">
<div id="container">
<div id="header">
   <div id="topmenu">
<?php
echo _skin_top_menu($_top_menu);
?>   
   </div>
   <div id="top-panel">
   <?php echo (isset($skin_sub_menu)) ? $skin_sub_menu : ''; ?>
   </div>
</div>
<div id="wrapper">
<div id="content">
<?php

echo ON_Say::get();

if (isset($output)) {
    echo $output;
}

?>
</div>
<?php

if (isset($show) && $show['rightcolumn']==true) {

?>
            <div id="sidebar">
  				<ul>
  <li><h3><a href="#" class="house"><?php echo _('Web Site'); ?></a></h3>

                        <ul>
  <li><a href="product_search.php" class="replace"><?php echo _('Search'); ?></a></li>

                        </ul>
                    </li>
  <li><h3><a href="#" class="payments"><?php echo _('Orders'); ?></a></h3>
          				<ul>
  <li><a href="list_payments.php" class="paymentsnew"><?php echo _('New Orders'); ?></a></li>
                        </ul>
                    </li>
  <li><h3><a href="#" class="products"><?php echo _('Products'); ?></a></h3>
          				<ul>
  <li><a href="list_products.php" class="productslist"><?php echo _('Products'); ?></a></li>
  <li><a href="product.php" class="productsnew"><?php echo _('New Product'); ?></a></li>
  <li><a href="category.php" class="catlst"><?php echo _('New Category'); ?></a></li>
                        </ul>
                    </li>
  <li><h3><a href="#" class="pages"><?php echo _('Content'); ?></a></h3>
          			       <ul>
  <li><a href="news.php" class="pagenew"><?php echo _('New News'); ?></a></li>
  <li><a href="newsletter.php" class="newsletter"><?php echo _('New E-Bulletin'); ?></a></li>
                        </ul>
                    </li>
				</ul>       
          </div>
<?php
}
?>
<!-- center --> </div>

        <div id="footer">
        <div id="credits">

  Template <a href="http://www.bloganje.com">Bloganje</a>

        </div>
        <div id="styleswitcher">
            <ul>
                <li><a href="javascript: document.cookie='theme='; window.location.reload();" title="<?php echo _('Red'); ?>" id="defswitch">d</a></li>
                <li><a href="javascript: document.cookie='theme=1'; window.location.reload();" title="<?php echo _('Blue'); ?>" id="blueswitch">b</a></li>
                <li><a href="javascript: document.cookie='theme=2'; window.location.reload();" title="<?php echo _('Green'); ?>" id="greenswitch">g</a></li>
                <li><a href="javascript: document.cookie='theme=3'; window.location.reload();" title="<?php echo _('Brown'); ?>" id="brownswitch">b</a></li>
                <li><a href="javascript: document.cookie='theme=4'; window.location.reload();" title="<?php echo _('Mix'); ?>" id="mixswitch">m</a></li>
            </ul>
        </div><br />

        </div>
</div>
<?php
  echo '<!-- time:'.stop_timing().' -->';
?>
</body>
</html>
