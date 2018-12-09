<?php

define('ENVIRONMENT','ajax');
include 'init.php';

$act = ON_Filter($_GET['act']);

switch($act) {
case 'stock':
case 'favor':
  //  onoff($act, $_GET['productid'], getLoginID());
  break;
default:// failed
  die(0);
  break;
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
	echo $encid.'|2';// deleted successfully
      } else {
	$db->exec('INSERT INTO '.$tbl . '(productid, userid)'.
		  ' VALUES('.$productid.','.(int)$userid.')');
	echo $encid.'|1';// inserted successfully
      }
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }
}



?>