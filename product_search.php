<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();

$product = new ON_Product();  

$output = '';

if (isset($_GET['sw'])) {  
  $sw = ON_Filter($_GET['sw']);
  $aSearch = explode(' ', trim($sw));

  $where = ' AND (';
  foreach($aSearch as $term) {
    $where .= ' (a.productname LIKE "'.$term.'%" OR a.productdetail LIKE "%'.$term.'%") OR';
    //postgre
    //$where .=  ' (to_tsvector(productdetail || productname) @@ to_tsquery('.$product->quote($term).')) OR';    
  }
  $where = substr($where, 0, -2);
  $where .= ')';

  $res = $product->pager($pager, $numrows, $where);

  if ($res) {
    $_options = array('detail.php?'                    => _('Detail'),
                        );

    $inline_form =& new ON_LinkForm($_options);
    $t = new ON_Table($inline_form);
    $t->title(array(_('Product Name'), _('Category'), _('Price'), _('Description')));  
    $e = new ON_Enc();

    $cat = new ON_Cat();
    $aCats = $cat->getCats();

    while($p = $res->fetch(PDO::FETCH_OBJ)) {
      $product->getfilter($p);
      $t->tr(array($p->productname, $aCats[$p->catid], $p->price, truncate(clearCodes($p->productdetail))), 
	     $e->encrypt($p->productid));      
    }
    $output .= sprintf(_('%s result found'), $numrows);
    $output .= '<br clear="all" />';
    $output .= $pager->display();
    $output .= $t->display();
    $output .= '<br clear="all" />';
    $output .= $pager->display();
  } else {
    $output .= _('Result not found, please try again different terms or use detail search page');
  }
}


include PT_INCLUDE . 'ON_Theme.php';
theme($output);

?>