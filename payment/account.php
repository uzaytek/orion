<?php

include '../init.php';
define('ON_CCARD_REQUIRED', false);
include '../includes/payment/ON_Account.php';

$accountid = (isset($_REQUEST['accountid'])) ? $_REQUEST['accountid'] : 0;
$account = new ON_PaymentAccount();

$enc = new ON_Enc();
if ($accountid) {
  $accountid = (int)$enc->decrypt($accountid);
  $bload = $account->load($accountid);
}

if ($bload) {

  $user =& ON_User::getInstance();
  $user->load($session->get('userid'));

  $basket = new ON_Basket();

  $payment =& ON_Payment::getInstance($user, $basket);

  $output = $basket->display($user->userid, $payment->getPaymentKey());

  $acc = new ON_Account();

  $acc->paymentForm($user);

  // process form if posted
  if ($acc->form->isSubmitted() && $acc->form->validate()) {
    $eReview =& $acc->form->getElement('reviewok');
    $values =& $acc->form->exportValues();
    if(1 == (int)$values['reviewok']) {
      $payment->insert($paymentid, ON_PAYMENT_ACCOUNT, $account->accountid, $account->title, $values);
      ON_Say::add(fmtSuccess(_('Information saved successfully')));
    } else {
      $eReview->setValue(1);
      $acc->form->freeze();
      $output .= $acc->form->toHtml();
    }
  } else {
    $output .= $acc->form->toHtml();
  }
}

$settings = new ON_Settings();
$settings->load();

include PT_INCLUDE . 'ON_Theme.php';
theme($output);

?>