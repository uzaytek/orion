<?php

include 'init.php';

$enc_productid = $productid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
$enc = new ON_Enc();
$productid = (int) $enc->decrypt($productid);

if ($productid == 0) {
  die(_('Product ID not found'));
}

$image = new ON_Image($productid);
$image->registerForm('uploadImage');

$imgid = (isset($_REQUEST['imgid'])) ? $_REQUEST['imgid'] : 0;
if ($imgid) {
  $imgid = (int) $enc->decrypt($imgid);
  if (isset($_REQUEST['act']) && strstr($_REQUEST['act'], 'delimage')) {
    $result = $image->isupdate('isdeleted=1,dtdeleted=NOW()',$imgid);
    if ($result) {
      ON_Say::add(fmtSuccess(_('Image deleted successfully')));
    }
  }
  if (isset($_REQUEST['act']) && strstr($_REQUEST['act'], 'makedefault')) {
    $result = $image->isupdate('isdefault=0',' WHERE productid='.$productid);
    $result = $image->isupdate('isdefault=1',$imgid);
    if ($result) {
      ON_Say::add(fmtSuccess(_('Image set to default image successfully')));
    }
  }
}

// fill form with elements
$image->fillForm();

// process upload form if posted
$output = '';
if ($image->form->isSubmitted() && $image->form->validate()) {
  
  $file =& $image->form->getElement('image_1');
  if ($file->isUploadedFile()) {
    // upload and insert database
    if ($image->setValues($file) && $image->insert($imgid)) {
      // if successfull create thumbnail
      $thumb = new ON_Thumbnail($image->getName());
      $thumb->save();
      $image->isupdate('isdefault=0',' WHERE productid='.$productid);
      $image->isupdate('isdefault=1',$imgid);
      $output = fmtSuccess(_('Product image uploaded successfully'));  	
    } else {
      $output = fmtError(_('Product image upload failed'));  	
    }
  } else {
    $output = fmtError(_('Product image can\'t upload to server'));
  }
}

$output .= $image->form->toHtml();

// list all product images
$image->setWhere('(isdeleted=0 OR isdeleted IS NULL) AND productid='.$productid);
$image->setOrder(' isdefault DESC ');
$arr_images = $image->getAll();
foreach ($arr_images as $arr_image) {
  $thumb_name = 'thumb_' . $arr_image['hostfilename'] . $arr_image['fileextension'];
  $enc_imgid = $enc->encrypt($arr_image['imgid']);
  $thumb_path = PT_IMAGE . $thumb_name;
  if (file_exists($thumb_path)) {
    $thumb_image = getimagesize($thumb_path);
    $default_image = ($arr_image['isdefault'] == 1) ? '<img src="./img/icons/tick.png">' : '';
    $output .= '<div class="aimage" style="background-image:url(' . 
      LC_IMAGE . $thumb_name.');background-repeat:no-repeat;width:'.$thumb_image[0].'px;height:'.$thumb_image[1].'px;" />'.
	'<a href="?act=makedefault&id='.$enc_productid.'&imgid='.$enc_imgid.'"><img src="./img/icons/add.png" border=0></a>'.
	'<a href="?act=delimage&id='.$enc_productid.'&imgid='.$enc_imgid.'"><img src="./img/icons/cross.png" border=0></a>'.
      $default_image.'</div><br>';
  }
}


include 'theme.php';

?>
