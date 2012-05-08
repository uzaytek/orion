<?php
include 'init.php';

$user =& ON_User::getInstance();
$user->registerForm('registerUser');

$userid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $user->defaults();
if ($userid) {
  $userid = (int)$enc->decrypt($userid);
  $bupdate = $user->load($userid);
}

// fill form with elements
$user->fillRegisterForm();

$output = '';
if ($user->form->isSubmitted() && $user->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $user->update($userid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('User updated successfully')));
      header('Location: list_users.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('User update failed')));
    }
  } else {
    $res = $user->insert($userid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('User inserted successfully')));  
      $user->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('User insert failed')));
    }
  }
}

$output .= $user->form->toHtml();
include 'theme.php';

?>