<?php
include 'init.php';

$gate = new ON_PaymentGateway();
$gate->registerForm('registerGateway');

$gateid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $gate->defaults();
if ($gateid) {
  $gateid = (int)$enc->decrypt($gateid);
  $bupdate = $gate->load($gateid);
}

// fill form with elements
$gate->fillForm();

$output = '';
if ($gate->form->isSubmitted() && $gate->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $gate->update($gateid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Payment gateway updated successfully')));
      header('Location: list_payment_gateways.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('Payment gateway update failed')));
    }
  } else {
    $res = $gate->insert($gateid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Payment gateway inserted successfully')));  
      $gate->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Payment gateway insert failed')));
    }
  }
}

$output .= $gate->form->toHtml();
include 'theme.php';

?>