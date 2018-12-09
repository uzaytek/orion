<?php

define('ENVIRONMENT','ajax');
include 'init.php';

$act = ON_Filter($_GET['act']);

switch($act) {
case 'bulletinSubscribe':
  Subscribe($_GET['email']);
  break;
case 'basket':
  basket($act, ON_Filter($_GET['productid']));
  break;
case 'stock':
case 'favor':
  $user =& ON_User::getInstance();
  onoff($act, ON_Filter($_GET['productid']), $user->getLoginID());
  break;
default:// failed
  die(0);
  break;
}

function Subscribe($email) {
  $user =& ON_User::getInstance();
  $ret = $user->insertBulletinSubscription($email);
  if ($ret) {
    // $newmail = new ON_Mail('subscribe', array('to'=>$email));
    // $newmail->send();
    echo '1';// inserted successfully    
  }
}

function basket($act, $encid) {
  $payment =& ON_Payment::getInstance();
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
    $user =& ON_User::getInstance();
    $basket->userid     = $user->getLoginID();
    $flag = $basket->insert($basketid);
    //error_log("basketid: $basketid\n", 3, PT_ERRLOGS);
    if ($flag) {
      echo $uniqid.'|1';
    }
  }
}


function onoff($act, $encid, $userid) {
  switch($act) {
  case 'favor':
    $tbl = DB_TBL_PRODUCT_FAVORS;
    break;
  case 'stock':
    $tbl = DB_TBL_STOCK_ALARMS;
    break;
  default:
    die('Undefined page action');
    break;
  }
  $enc = new ON_Enc();
  $productid = (int)$enc->decrypt($encid);
  if ($productid > 0 && $userid > 0) {  
    try {
      $db = connect();
      $st = $db->query('SELECT COUNT(*) FROM '. $tbl . 
		       ' WHERE productid='.$productid.' AND userid='.(int)$userid);
      if ($st->fetchColumn()) {
	$db->exec('DELETE FROM ' . $tbl .
		  ' WHERE productid='.$productid.' AND userid='.(int)$userid);
	echo $uniqid.'|2';// deleted successfully
      } else {
	$db->exec('INSERT INTO '.$tbl . '(productid, userid)'.
		  ' VALUES('.$productid.',' . (int)$userid . ')');
	echo $uniqid.'|1';// inserted successfully
      }
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }
}

?>