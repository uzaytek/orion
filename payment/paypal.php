<?php

include '../init.php';
define('ON_CCARD_REQUIRED', true);
include '../includes/payment/ON_PayPal.php';

$output = '';

$user =& ON_User::getInstance();
$user->load($session->get('userid'));

$basket = new ON_Basket();

$payment =& ON_Payment::getInstance($user, $basket);

$output = $basket->display($user->userid, $payment->getPaymentKey());


$paypal = new ON_PayPal();
$paypal->directPaymentForm($user);

// process forms if directPayment form posted
if ($paypal->form->isSubmitted() && $paypal->form->validate()) {
  $eReview =& $paypal->form->getElement('reviewok');
  $values =& $paypal->form->exportValues();
  if(1 == (int)$values['reviewok']) {
    if(true) { //&& $paypal->requestPayment($aPaymentResponse, 'DirectPayment', $ccAmount)) {

      $payment->insert($paymentid, ON_PAYPAL_DIRECT_PAYMENT, $values);
      ON_Say::add(fmtSuccess(_('Payment Successfull')));
    } else {
      ON_Say::add(fmtError(_('Payment Failed')));
    }
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