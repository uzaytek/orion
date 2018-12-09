<?php

include 'init.php';

$output = '';
$enc = new ON_Enc();
$id = ON_Filter($_REQUEST['id']);
$productid = (int)$enc->decrypt($id);

$user =& ON_User::getInstance();
if ($session->ifset('userid')) {
  $user->load($session->get('userid'));
}

$product = new ON_Product();
$tpl = new ON_Display('ProductDetail');
$numrows = 1;
$res = $product->pager($pager, $numrows, null, ' AND a.productid='.$productid);
$p = $res->fetch(PDO::FETCH_OBJ);
if ($p) {
  $product->getfilter($p);

  $productstat = new ON_Stat();
  $useraction  =  $user->userid ? ON_USER_ACTION_VIEW : ON_ANON_ACTION_VIEW ;  
  $aStatValues = array('itemid'=>$productid, 'stattype'=>ON_STAT_PRODUCT,
                       'useraction'=> $useraction);  
  $productstat->setValues($aStatValues);
  $productstat->insert();

  $tpl->title($p->productname);
  $tpl->addRow($p);
  $output .= $tpl->display();
} else {
  $output = _('Product not found');
}


$settings = new ON_Settings();
$settings->load();

include PT_INCLUDE . 'ON_Theme.php';
theme($output);


?>