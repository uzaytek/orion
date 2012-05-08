<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();
$act    = (isset($_REQUEST['act'])) ? ON_Filter($_REQUEST['act']) : '';
$encid  = (isset($_REQUEST['id'])) ? ON_Filter($_REQUEST['id']) : '';

$output = '';

if (!$session->ifset('userid')) {
  ON_Say::add(fmtSuccess(_('Please login to your account')));
  $aReturnUrl = parse_url(ON_Filter($_SERVER['REQUEST_URI']));
  $returnUrl = $aReturnUrl['path'] . '?' . $aReturnUrl['query'];
  header('Location: ' . LC_SITE . 'login.php?returnUrl=' . urlencode($returnUrl));
  exit;
}

if (isset($_REQUEST['go'])) {
  // comes from basket.php forms
  // update category orders
  if ($_REQUEST['submitUpdateCounts']) {
    $basket = new ON_Basket();
    $result = $basket->updateItemCounts($_POST['txtUpdateCounts']);
    if ($result) {
      ON_Say::add(fmtSuccess(_('Basket product counts updated successfully')));
    }
  } // end update orders
}

switch ($act) {
// comes from other pages
case 'add':
  $payment =& ON_Payment::getInstance($user, $basket);
  $enc = new ON_Enc();
  $productid = (int)$enc->decrypt($encid);
  //  echo $productid;
  $product = new ON_Product();
  $product->load($productid);
  //  error_log(" productid: $productid\n", 3, PT_ERRLOGS);
  if ($productid > 0) {  
    $basket = new ON_Basket();
    $basket->productid  = (int)$productid;
    $basket->paymentkey = $payment->getPaymentKey();
    $basket->price      = $product->price;
    $session =& ON_Session::instance();
    $basket->userid     = (int)$session->get('userid');
    $flag = $basket->insert($basketid);
    if ($flag) {
      $output .= fmtSuccess(_('Product added to your basket sucessfully'));
    } else {
      $output .= fmtError(_('Product add failed, basket insert fail'));
    }
  }
  break;
case 'delete':
  $enc = new ON_Enc();
  $basketid = (int)$enc->decrypt($encid);
  $basket = new ON_Basket();
  if ($basket->delete($basketid)) {
    $output .= fmtSuccess(_('Product deleted from basket sucessfully'));
  }
  break;
}

$basket = new ON_Basket();
$payment =& ON_Payment::getInstance($user, $basket);

$enc = new ON_Enc();
if ($basket->load($res, $session->get('userid'), $payment->getPaymentKey())) {
  $block_form  = new ON_BlockForm('block'); // wrap to table with a form for catorder text box
  $t = new ON_Table(null, $block_form);
  $t->title(array(_('Product Name'), _('Price'), _('Count'), _('Sub Total'),'','',''));
  $total = 0;

  while($row = $res->fetch()) {
    $subtotal = $row['price'] * $row['itemcount'];
    $total += $subtotal;
    // 
    $payment->getfilter($row);
    $encid     = $enc->encrypt($row['basketid']);        
    $xdelete = '<div><span><a href="basket.php?act=delete&id='.$encid.'">'._('Delete').'</a></span></div>';
    
    $t->tr(array($row['productname'], fmtPrice($row['price']), 
                 $block_form->textElement('txtUpdateCounts['.$encid.']', intval($row['itemcount'])),
                 fmtPrice($subtotal), $xdelete));
  }
  $t->addRow(array('&nbsp;','&nbsp;','&nbsp;',_('Total:&nbsp;') . fmtPrice($total)));
  $t->addRow($block_form->submit('submitUpdateCounts', _('Update Counts')));
  $output .= $t->display();
  $output .= $payment->paymentOptions();
} else {
  $output = fmtWarn(_('Not found any product in your basket'));
}  

include PT_INCLUDE . 'ON_Theme.php';
theme($output);
?>