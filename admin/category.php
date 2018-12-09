<?php
include 'init.php';

$cat = new ON_Cat();
$cat->registerForm('registerCat');

$catid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $cat->defaults();
if ($catid) {
  $catid = (int)$enc->decrypt($catid);
  $bupdate = $cat->load($catid);
}

// fill form with elements
$cat->fillForm();

$output = '';
if ($cat->form->isSubmitted() && $cat->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $cat->update($catid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Category updated successfully')));
      header('Location: list_categories.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('Category update failed')));
    }
  } else {
    $res = $cat->insert($catid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Category inserted successfully')));  
      $cat->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Category insert failed')));
    }
  }
}

$output .= $cat->form->toHtml();
include 'theme.php';

?>