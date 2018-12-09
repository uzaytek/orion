<?php
include 'init.php';

$newsletter = new ON_Newsletter();
$newsletter->registerForm('registerBulletin');

$letterid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $newsletter->defaults();
if ($letterid) {
  $letterid = (int)$enc->decrypt($letterid);
  $bupdate = $newsletter->load($letterid);
}

// fill form with elements
$newsletter->fillForm();

$output = '';

$user =& ON_User::getInstance();
$usercounts = $user->numRowsBulletinUsers();
$output .= sprintf(_('Registered e-bulletin member count %s'), $usercounts);


if ($newsletter->form->isSubmitted() && $newsletter->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $newsletter->update($letterid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Newsletter updated successfully')));
      header('Location: list_newsletters.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('Newsletter update failed')));
    }
  } else {
    $res = $newsletter->insert($letterid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Newsletter inserted successfully')));  
      $newsletter->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Newsletter insert failed')));
    }
  }
}

$output .= $newsletter->form->toHtml();
include 'theme.php';

?>