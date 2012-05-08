<?php

include '../init.php';
define('AN_CCARD_REQUIRED', false);
include '../includes/payment/AN_PayPal.php';

$user =& ON_User::getInstance();
$user->load($session->get('userid'));

$basket = new AN_Basket();

$payment =& AN_Payment::getInstance($user, $basket);

$output = $basket->display($user->userid, $payment->getPaymentKey());


$paypal = new AN_PayPal();

// redirect paypal, take token, payment
/*
  if(!AN_PayPal::reDirectToPayPal($oProfile, $outError, $sType, $nID, $aSelectedService['ccBaseAmount'])) {
  error_log('redirect paypal' . $outError);
  Say::add(fmtError("Paypal yönlendirmesi başarısız oldu, lütfen daha sonra tekrar deneyin"));
  }
*/

$paypal->expressCheckoutForm($user);

// process form if posted
if ($paypal->form->isSubmitted() && $paypal->form->validate()) {
  $eReview =& $paypal->form->getElement('reviewok');
  $values =& $paypal->form->exportValues();

  if(1 == (int)$values['reviewok']) {


      $payment->insert($paymentid, AN_PAYPAL_EXPRESS_CHECKOUT, $values);

      Say::add(fmtSuccess(_('Payment Successfull')));
  } else {
    $eReview->setValue(1);
    $paypal->form->freeze();
    $output .= $paypal->form->toHtml();
  }
} else {
  $output .= $paypal->form->toHtml();
}


$settings = new ON_Settings();
$settings->load();

include PT_INCLUDE . 'ON_Theme.php';
theme($output);


?>