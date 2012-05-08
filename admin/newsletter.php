<?php

include 'init.php';
$output = '';

$newsletter = new ON_Newsletter();

$newsletter->registerForm('newsletter');

// fill form with elements
$newsletter->fillForm();
$user =& ON_User::getInstance();
$usercounts = $user->numRowsBulletinUsers();
$output .= sprintf(_('Registered e-bulletin member count %s'), $usercounts);

// process forms if posted
if ($newsletter->form->isSubmitted() && $newsletter->form->validate()) {
  $values =& $newsletter->form->exportValues();
  $newsletter->setValues($values);
  
  if($newsletter->globalid > 0) {
    $res = $newsletter->update();
    if ($res) {
      ON_Say::add(fmtSuccess(_('Newsletter updated successfully')));
    } else {
      ON_Say::add(fmtError(_('Newsletter update failed')));
    }
  } else {
    $res = $newsletter->insert();
    if ($res) {
      ON_Say::add(fmtSuccess(_('Newsletter inserted successfully')));  
      } else {
      ON_Say::add(fmtError(_('Newsletter insert failed')));
    }
  }
}


$output .= $newsletter->form->toHtml();
include 'theme.php';

?>