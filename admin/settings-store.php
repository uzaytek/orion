<?php

include 'init.php';

$output = '';

$setup = new ON_Settings();
$setup->load();

$setup->registerForm('setup');

// fill form with elements
$setup->fillForm();

// process forms if posted
if ($setup->form->isSubmitted() && $setup->form->validate()) {
  $values =& $setup->form->exportValues();
  $setup->setValues($values);
  
  if($setup->globalid > 0) {
    $res = $setup->update();
    if ($res) {
      ON_Say::add(fmtSuccess(_('Settings updated successfuly')));
      $defaults = $setup->defaults();
      $setup->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Database error: settings update failed')));
    }
  } else {
    $res = $setup->insert();
    if ($res) {
      ON_Say::add(fmtSuccess(_('Settings inserted successfuly')));  
    } else {
      ON_Say::add(fmtError(_('Database error: settings insert failed')));
    }
  }
}

$output .= $setup->form->toHtml();
include 'theme.php';

?>