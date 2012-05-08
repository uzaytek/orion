<?php
include 'init.php';

$faq  = new ON_Faq();

// delete
if (isset($_POST['go'])) {
  if (strstr($_POST['go'], 'delete_faq') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $faqid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $faq->load($faqid);
    if($bupdate) {        
      $result = $faq->isupdate('isdeleted=1', $faqid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected faq deleted successfully')));
      }
    }
  }
}

$faq->setOrder('faqtitle');
$res = $faq->pager($pager, $numrows);

if ($res) {

  $inline_form = new ON_SelectForm(array('faq.php'                     =>_('Modify'), 
                                         'list_faqs.php#delete_faq'    =>_('Delete')));
  $t = new ON_Table($inline_form);
  $t->title(array(_('Faq Title'), _('Faq Detail')));
  $e = new ON_Enc();

  while($row = $res->fetch()) {
    $t->tr(array($row['faqtitle'], truncate(clearCodes($row['faqdetail']))),
	   $e->encrypt($row['faqid']));
  }
  $t->addRow($pager->display()._('Total Faq Count:').$numrows, $t->colCount());
  $output = $t->display();
} else {
  $output = fmtError(_('Faq record not found'));
}

include 'theme.php';

?>