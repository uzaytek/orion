<?php
include 'init.php';

$news  = new ON_News();

// delete
if (isset($_POST['go'])) {
  if (strstr($_POST['go'], 'delete_news') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $newsid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $news->load($newsid);
    if($bupdate) {        
      $result = $news->isupdate('isdeleted=1', $newsid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected news deleted successfully')));
      }
    }
  }
}

$news->setOrder('newstitle');
$res = $news->pager($pager, $numrows);

if ($res) {

  $inline_form = new ON_SelectForm(array('news.php'                     =>_('Modify'), 
                                         'list_news.php#delete_news'    =>_('Delete')));
  $t = new ON_Table($inline_form);
  $t->title(array(_('News Title'), _('News Detail')));
  $e = new ON_Enc();

  while($row = $res->fetch()) {
    $t->tr(array($row['newstitle'], truncate(clearCodes($row['newsdetail']))),
	   $e->encrypt($row['newsid']));
  }
  $t->addRow($pager->display()._('Total News Count:').$numrows, $t->colCount());
  $output = $t->display();
} else {
  $output = fmtError(_('News record not found'));
}

include 'theme.php';

?>