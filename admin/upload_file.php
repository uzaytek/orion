<?php

include 'init.php';

$output = '';

$sfileid = (isset($_REQUEST['fileid'])) ? filter_var($_REQUEST['fileid']) : 0;

$afile    = new ON_File();

// delete
if (isset($_POST['go'])) {
  if (strstr($_POST['go'], 'delete') && isset($sfileid)) {
    $enc = new ON_Enc();
    $fileid = (int)$enc->decrypt($sfileid);
    $bupdate = $afile->load($fileid);
    if($bupdate) {        
      $result = $afile->isupdate('isdeleted=1', $fileid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('File deleted successfully')));
      }
    }
  }

  if (strstr($_POST['go'], 'download') && isset($sfileid)) {
    $enc = new ON_Enc();
    $fileid = (int)$enc->decrypt($sfileid);
    $bupdate = $afile->load($fileid);
    if($bupdate) {        
      if (@file_exists(PT_UPLOAD . $afile->filename)) {
        header('Content-Disposition: inline; filename="' . $afile->origname.'"');
        header('Content-transfer-encoding: binary');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/force-download',  true);
        header('Content-Type: application/download', true);
        readfile(PT_UPLOAD . $afile->filename);
        exit;
      }
    }
  }
}


$enc = new ON_Enc();

$afile->type = 'firm';

$afile->registerForm();
$afile->fillForm();

// process forms
if ($afile->form->validate()) {

  $values =& $afile->form->exportValues();  

  if($afile->setValues($values, PT_UPLOAD)) {      
    if($afile->insert()) {
      ON_Say::add(fmtSuccess(_('File uploaded successfully')));
    } else {
      ON_Say::add(fmtError(_('File insert failed')));
    }
  } else {
    ON_Say::add(fmtError(_('File upload failed')));
  }
}
$output = $afile->form->toHtml();
$where = 'fkid = '.$afile->fkid;
$res = $afile->pager($pager, $numrows, $where);

if ($res) {
  $output .= "<br />";
    
  $inline_form = new ON_SelectForm(array('upload_file.php#download'      => _('Download'), 
                                         'upload_file.php#delete'        => _('Delete')), 'fileid');
  $t = new ON_Table($inline_form);
  $t->title(array(_('Name'), _('Upload Date')));
  $e = new ON_Enc();
    
  while($row = $res->fetch()) {
    $t->tr(array($row['origname'], $afile->getDate('o', $row['dtcreated'])),
           $e->encrypt($row['fileid']));
    }
  $t->addRow($pager->display()._('Total File Count:') . $numrows);
  $output .= $t->display();
}


include 'theme.php';

?>
