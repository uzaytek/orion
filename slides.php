<?php

include 'init.php';

$enc_productid = $productid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;
$enc = new ON_Enc();
$productid = (int) $enc->decrypt($productid);

$image = new ON_Image(0, $productid);
$image->setWhere('(isdeleted=0 OR isdeleted IS NULL) AND productid='.$productid);
$image->setOrder(' isdefault DESC ');
$arr_images = $image->getAll();
$output = '
<script language="javascript" type="text/javascript" src="' . LC_SITE . 'assets/slideshow.js"></script>
<script type="text/javascript">
<!--
SLIDES = new slideshow("SLIDES");
';
foreach ($arr_images as $arr_image) {
  $image_name = $arr_image['hostfilename'] . $arr_image['fileextension'];
  if(!isset($firstimage)) {$firstimage = LC_IMAGE.$image_name;};
  $image_path = PT_IMAGE . $image_name;
  if (file_exists($image_path)) {
    $output .= 'SLIDES.add_slide(new slide("'.LC_IMAGE.$image_name.'","'.LC_IMAGE.$image_name.'"));';
     
  }
}

$output .= '
//-->
</SCRIPT>

<P>

<IMG name="SLIDESIMG" src="'.$firstimage.'" alt="Slideshow image"></A>


<FORM>
<INPUT TYPE="button" VALUE="'._('Start').'" onClick="SLIDES.next();SLIDES.play()">
<INPUT TYPE="button" VALUE="'._('Stop').'" onClick="SLIDES.pause()">
<INPUT TYPE="button" VALUE="'._('Display').'" onClick="SLIDES.hotlink()">
<INPUT TYPE="button" VALUE="'._('Previous').'" onClick="SLIDES.previous()">
<INPUT TYPE="button" VALUE="'._('Next').'" onClick="SLIDES.next()">
</FORM>

<P>
<SCRIPT type="text/javascript">
<!--
if (document.images) {
  SLIDES.image = document.images.SLIDESIMG;
  SLIDES.textid = "SLIDESTEXT";
  SLIDES.update();
  SLIDES.play();
}
//-->
</SCRIPT>

';

$onloadjs = 'onLoad="SLIDES.update()"';


$settings = new ON_Settings();
$settings->load();


include PT_INCLUDE . 'ON_Theme.php';
theme_popup($output);

?>