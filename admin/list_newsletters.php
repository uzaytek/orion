<?php
include 'init.php';

$newsletter  = new ON_Newsletter();

// delete
if (isset($_POST['go'])) {
  if (strstr($_POST['go'], 'delete_newsletter') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $letterid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $newsletter->load($letterid);
    if($bupdate) {        
      $result = $newsletter->isupdate('isdeleted=1', $letterid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected newsletter deleted successfully')));
      }
    }
  }
}

$res = $newsletter->pager($pager, $numrows);

if ($res) {

  $inline_form = new ON_SelectForm(array('newsletter.php'                           =>_('Modify'), 
                                         'send_newsletter.php'                      =>_('Send E-bulletin'), 
                                         'list_newsletters.php#delete_newsletter'    =>_('Delete')));
  $t = new ON_Table($inline_form);
  $t->title(array(_('Newsletter Title'), _('Newsletter Detail')));
  $e = new ON_Enc();

  while($row = $res->fetch()) {
    $t->tr(array($row['lettersubject'], truncate(clearCodes($row['letterbody']))),
	   $e->encrypt($row['letterid']));
  }
  $t->addRow($pager->display()._('Total Newsletter Count:').$numrows, $t->colCount());
  $output = $t->display();
} else {
  $output = fmtError(_('Newsletter record(s) not found'));
}

include 'theme.php';

?>