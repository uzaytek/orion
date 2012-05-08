<?php

include 'init.php';

$banner    = new ON_Banner();
$banner->registerForm();

$fileid = (isset($_REQUEST['id'])) ? filter_var($_REQUEST['id']) : 0;

$bupdate = false;
$defaults = $banner->defaults();
if ($fileid) {
  $bupdate = $banner->load($fileid);
}

// fill form with elements
$banner->fillForm();
$output = '';

// process forms
if ($banner->form->isSubmitted() && $banner->form->validate()) {

  $values =& $banner->form->exportValues();
  $banner->setValues($values, PT_UPLOAD);

  if (isset($bupdate) && $bupdate==true) {
    $res = $banner->update($fileid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Banner modified successfuly')));
    } else {
      ON_Say::add(fmtError(_('Database error: banner update failed')));
    }
  } else {
    $res = $banner->insert($fileid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Banner inserted successfuly')));  
      $banner->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Database error: banner insert failed')));
    }
  }
}

$output = $banner->form->toHtml();

include 'theme.php';

?>