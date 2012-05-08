<?php

include 'init.php';

$fileid = (isset($_REQUEST['id'])) ? filter_var($_REQUEST['id']) : 0;

$banner    = new ON_Banner();

if (isset($_POST['go'])) {
  // delete  
  if (strstr($_POST['go'], 'delete_banner') && isset($fileid)) {
    $bupdate = $banner->load($fileid);
    if($bupdate) {        
      $result = $banner->isupdate('isdeleted=1', $fileid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('File deleted successfuly')));
        $fileid=0; // dont load delete file again
      }
    }
  }//delete_banner
}


$res = $banner->pager($pager, $numrows);
$output = '';

if ($res) {
  $output .= "<br />";
  
  $inline_form = new ON_SelectForm(array('banner.php'                         => _('Modify'), 
                                         'list_banner.php?go=delete_banner'   => _('Delete')));
  $t = new ON_Table($inline_form);
  $t->title(array(_('Name'), _('Location'), _('Url'), _('Upload Date')));
  $aLocation = $banner->getLocations();
  while($row = $res->fetch()) {
    $t->tr(array($row['origname'], $aLocation[$row['location']], $row['url'], $banner->getDate('o', $row['dtcreated'])),
           $row['fileid']);
  }
  $t->addRow($pager->display()._('Total File Count:').$numrows, $t->colCount());
  $output .= $t->display();
} else {
  $output = fmtError(_('Banner record not found'));
}

include 'theme.php'; 

?>