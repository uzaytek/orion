<?php
include 'init.php';

$account = new ON_PaymentAccount();
$account->registerForm('paymentAccount');

$accountid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $account->defaults();
if ($accountid) {
  $accountid = (int)$enc->decrypt($accountid);
  $bupdate = $account->load($accountid);
}

// fill form with elements
$account->fillForm();

$output = '';
if ($account->form->isSubmitted() && $account->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $account->update($accountid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Payment account updated successfully')));
      header('Location: list_payment_accounts.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('Payment account update failed')));
    }
  } else {
    $res = $account->insert($accountid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Payment account inserted successfully')));  
      $account->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Payment account insert failed')));
    }
  }
}

$output .= $account->form->toHtml();
include 'theme.php';

?>