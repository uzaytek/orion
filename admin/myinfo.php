<?php

include 'init.php';

$output = $adminid = '';

$admin     = new ON_Admin();

// modify a record, fetch database values
if ($session->ifset('auth')) {
  $auth = $session->get('auth');
  $adminid = $auth['adminid'];
  $admin->load(intval($adminid));

  $admin->update_form();
  
  // process forms
  if ($admin->form->validate()) {
    $values =& $admin->form->exportValues();    
    
    $admin->setValues($values);
    
    $res = $admin->update($admin->adminid);
    
    if ($res) {
      $output = fmtSuccess(_('Your informations updated successfuly'));
    }
  } else {
    $output = $admin->form->toHtml();
  } 
  
} else {
  $output = fmtError(_('Session expried, please login and try again'));  
}

include 'theme.php';

?>
