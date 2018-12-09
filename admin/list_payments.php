<?php

include 'init.php';

$payment =& ON_Payment::getInstance($user, $basket);

if (isset($_REQUEST['go']) && isset($_REQUEST['id'])) {
  $enc = new ON_Enc();
  $paymentid = (int)$enc->decrypt($_REQUEST['id']);
  $bupdate = $payment->load($paymentid);
  if($bupdate) {  
    // payment recevied
    if (strstr($_REQUEST['go'], 'payment_received')) {
      $result = $payment->isupdate('paymentstatus='.ON_PAYMENT_RECEIVED.', dtmodified=NOW()',$paymentid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Payment status marked as received')));
      }
    }

    // order completed
    if (strstr($_REQUEST['go'], 'order_completed')) {
      $result = $payment->isupdate('orderstatus='.ON_ORDER_COMPLETED.', dtmodified=NOW()',$paymentid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Order status marked as completed')));
      }
    }

    // order cancelled
    if (strstr($_REQUEST['go'], 'order_cancelled')) {
      $result = $payment->isupdate('orderstatus='.ON_ORDER_CANCELLED.', dtmodified=NOW()',$paymentid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Order status marked as cancelled')));
      }
    }
  }
}

$type = (isset($_GET['type'])) ? ON_Filter($_GET['type']) : null;
$output = $payment->listPayments($type);

if($output == '') {
  $output = fmtError(_('Payment record not found'));
}


include 'theme.php';

?>