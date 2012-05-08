<?php

include '../init.php';
define('ON_CCARD_REQUIRED', true);
include '../includes/payment/ON_Gateway.php';


if (!$session->ifset('userid')) {
  ON_Say::add(fmtSuccess(_('Please login to your account')));
  header('Location: ' . LC_SITE . 'login.php');
  exit;
}

$gateid = (isset($_REQUEST['gateid'])) ? $_REQUEST['gateid'] : 0;
$gate = new ON_PaymentGateway();

$enc = new ON_Enc();
if ($gateid) {
  $gateid = (int)$enc->decrypt($gateid);
  $bload = $gate->load($gateid);
}

if ($bload) {

  $user =& ON_User::getInstance();
  $user->load($session->get('userid'));

  $basket = new ON_Basket();

  $payment =& ON_Payment::getInstance($user, $basket);

  $output = $basket->display($user->userid, $payment->getPaymentKey());

  $gateway = new ON_Gateway();
  $gateway->paymentForm($user);

  // process forms if directPayment form posted
  if ($gateway->form->isSubmitted() && $gateway->form->validate()) {
    $eReview =& $gateway->form->getElement('reviewok');
    $values =& $gateway->form->exportValues();
    if(1 == (int)$values['reviewok']) {
      $ccAmount = $basket->getTotal($user->userid, $payment->getPaymentKey());
      $aPaymentResponse = array();
      if($gateway->requestPayment($ccAmount, $aPaymentResponse, $payment->getPaymentKey())) {
        $payment->insert($paymentid, ON_PAYMENT_GATEWAY, $gate->gateid, $gate->title, $values);
        ON_Say::add(fmtSuccess(_('Payment Successfull')));
      } else {
        ON_Say::add(fmtError(_('Payment Failed')));
      }
    } else {
      $eReview->setValue(1);
      $gateway->form->freeze();
      $output .= $gateway->form->toHtml();
    }
  } else {
    $output .= $gateway->form->toHtml();
  }
}

$settings = new ON_Settings();
$settings->load();

include PT_INCLUDE . 'ON_Theme.php';
theme($output);

?>