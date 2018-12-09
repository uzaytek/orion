<?php
include 'init.php';

$faq = new ON_Faq();
$faq->registerForm('registerFaq');

$faqid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $faq->defaults();
if ($faqid) {
  $faqid = (int)$enc->decrypt($faqid);
  $bupdate = $faq->load($faqid);
}

// fill form with elements
$faq->fillForm();

$output = '';
if ($faq->form->isSubmitted() && $faq->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $faq->update($faqid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Faq updated successfully')));
      header('Location: list_faqs.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('Faq update failed')));
    }
  } else {
    $res = $faq->insert($faqid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('Faq inserted successfully')));  
      $faq->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('Faq insert failed')));
    }
  }
}

$output .= $faq->form->toHtml();
include 'theme.php';

?>