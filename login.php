<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();

$user =& ON_User::getInstance();
$user->loginForm('loginUser', 'post', 'login.php');

if (isset($_REQUEST['returnUrl'])) {
  $user->form->addElement('hidden', 'returnUrl', ON_Filter($_REQUEST['returnUrl']));
}


$userid = $session->get('userid');
$output = '';
if ($userid > 0) {
  $output .= fmtSuccess(_('You are already logged in'));  
} else {
  // process login forms
  if ($user->form->validate()) {    
    $values =& $user->form->exportValues();
    $res = $user->login($values, $userid);
    if ($res) {
      $session->set('userid', $userid);
      $output .= fmtSuccess(_('Login sucessfull'));  
      if (isset($values['returnUrl'])) {
        $returnUrl = urldecode(ON_Filter($values['returnUrl']));
        $output .= '<meta http-equiv="refresh" content="5; url='.$returnUrl.'">';
        $output .= sprintf(_('Please wait we will return to entry point, if not work, <a href="%s">click here</a>'),
                           $returnUrl);                           
      }

    } else {
      $output .= fmtError(_('Login failed, please control your username/password'));
      $output .= $user->form->toHtml();
    }
  } else {
    $output = $user->form->toHtml();
  }  
}

include PT_INCLUDE . 'ON_Theme.php';
theme($output);
?>