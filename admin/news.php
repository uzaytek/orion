<?php
include 'init.php';

$news = new ON_News();
$news->registerForm('registerNews');

$newsid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

$enc = new ON_Enc();
$defaults = $news->defaults();
if ($newsid) {
  $newsid = (int)$enc->decrypt($newsid);
  $bupdate = $news->load($newsid);
}

// fill form with elements
$news->fillForm();

$output = '';
if ($news->form->isSubmitted() && $news->validate()) {
  if (isset($bupdate) && $bupdate==true) {
    $res = $news->update($newsid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('News updated successfully')));
      header('Location: list_news.php'. iif('currentpage'));
      exit;
    } else {
      ON_Say::add(fmtError(_('News update failed')));
    }
  } else {
    $res = $news->insert($newsid);
    if ($res) {
      ON_Say::add(fmtSuccess(_('News inserted successfully')));  
      $news->form->resetDefaults($defaults);
    } else {
      ON_Say::add(fmtError(_('News insert failed')));
    }
  }
}

$output .= $news->form->toHtml();
include 'theme.php';

?>