<?php

require_once 'HTML/Table.php';

/**
 * Web site display object
 *
 */
class ON_Display extends HTML_Table {

  /**
   * Encryption object for ids
   *
   * @var object
   */ 
  public $ob_enc;

  /**
   * hold all rows, display rows as seperate columns @see display
   * 
   * @var array
   */
  protected $rows = array();

  /**
   * Method name of what object will be displayed
   * 
   * @var string
   */
  protected $dispatcher;

  /**
   * row colspan values
   * 
   * @var array
   */
  protected $aColspan = array();

  /**
   * Constructor
   *
   * @param string $dispatcher The method name which will be used for display
   */ 
  public function ON_Display($dispatcher=null) {
    parent::__construct('border=0 cellpadding=0 cellspacing=0 id="zebraTable"');
    $this->ob_enc     = new ON_Enc();
    $this->dispatcher = $dispatcher;
  }

  /**
   * Add title row
   * 
   */  
  public function title($title, $colspan=0) {
    $col = parent::addRow(array($title));
    if ($colspan > 0) {
      $this->aColspan[$col] = $colspan;
    }
  }

  /**
   * Call dispatcher if available else parent addrow called
   * 
   * @param mixed $row added row
   * @param integer $colspan Colspan
   * @return integer Added row id
   */  
  public function addRow($row, $colspan=0) {
    if ($this->dispatcher && (method_exists($this, 'addRow'.$this->dispatcher))) { 
      $col = $this->{'addRow'.$this->dispatcher}($row);
    } else {
      $col =  parent::addRow($row);
    }
    return $col;
  }

  /**
   * Call dispatcher if available else $this -> toHTML called
   * 
   * @param mixed $row added row
   * @param integer $colspan Colspan
   * @return string table html
   */  
  public function display() {
    if ($this->dispatcher && (method_exists($this, 'display'.$this->dispatcher))) { 
      return $this->{'display'.$this->dispatcher}();
    } else {
      $this->setRowAttributes(0, 'class="title"', true);
      return $this->toHTML();
    }
  }

  /**
   * return detail link html
   * 
   * @param object $p Product object
   * @param string $encid The encrypted product id
   * @return string detail link html
   */  
  public function htmldetaillink(&$p, &$encid) {
    return '<div class="button"><a href="'.LC_SITE.'detail.php?id=' . $encid . '">'. _('Details') . '</a></div>';
  }

  /**
   * return basket link html
   * 
   * @param object $p Product object
   * @param string $encid The encrypted product id
   * @return string basket link html
   */  
  public function htmlbasketlink(&$p, &$encid) {
    return '<div class="button2"><a href="basket.php?act=add&amp;id='.$encid.'">'._('Add Basket').'</a></div>'; 
  }

  /**
   * return favorites link html
   * 
   * @param object $p Product object
   * @param string $encid The encrypted product id
   * @return string favorites link html
   */  
  public function htmlfavorlink(&$p, &$encid) {
    $output = '';
    if (isset($p->favor)) {
      $output = '<div><span id="favoroff'.$encid.'" '.hide(!$p->favor).
        '><a href="javascript:favor(\''.$encid.'\');">'.('Add to Favorites').'</a></span>'.
        '<span id="favoron' .$encid.'" '.hide($p->favor).
        '><a href="javascript:favor(\''.$encid.'\');">'._('Delete from Favorites').'</a></span></div>';      
    }
    return $output;
  }

  /**
   * return thumbnail image html
   * 
   * @param object $p Product object
   * @param string $encid The encrypted product id
   * @return string thumbnail image html
   */  
  public function htmlthumbnail(&$p, $encid) {
    $thumb_name = 'thumb_' . $p->hostfilename . $p->fileextension;
    $thumb_path = PT_IMAGE . $thumb_name;
    $output = '';
    if (file_exists($thumb_path)) {
      $output = '<div style="width:200px;height:200px;margin:0 auto;display:table-cell;vertical-align:middle"><a href="'.LC_SITE.'slides.php?id='.$encid.'" target="imageslides" '.
        'onclick="window.open(this.href,this.target,\'width=800,height=600\'); return false;">'.
        '<img border="0" src="' . LC_IMAGE . $thumb_name.'" /></a></div>';
    }
    return $output;
  }

  /**
   * return stock link html
   * 
   * @param object $p Product object
   * @param string $encid The encrypted product id
   * @return string stock link html
   */  
  public function htmlstocklink(&$p, &$encid) {
    $output = '';
    /*
    if ($p->stockcount == 0) {
      $output .= '<div><span id="stockoff'.$encid.'" '.hide(!$p->stockalarm).'>'.
        _('Already inserted to your subscriptions').
        ' <a href="javascript:stock(\''.$encid.'\');">'._('Delete Subscriptions').'</a></span>'.
        '<span id="stockon'.$encid.'" '. hide($p->stockalarm).'>'.
        _('Not in the stock').' <a href="javascript:stock(\''.$encid.'\');">'.
        _('Alert in the stock').'</a></span></div>';      
    }
    */
    return $output;
  }

  /**
   * return share link html
   * 
   * @param string $type Sharing type (product or category) TODO:not used
   * @param string $encid The encrypted product id
   * @return string share link html
   */  
  public function htmlsharelink($type, &$encid) {
    $output = '<div class="button"><a href="'.LC_SITE.'share.php?itemtype=product&amp;itemid='.
      $encid.'">'._('Share').'</a></div>';    
    return $output;
  }

  /**
   * add product to rows (dispatcher product)
   * 
   * @param object $p The product
   */  
  public function addRowProduct(&$p) {

    $user =& ON_User::getInstance();
    $session =& ON_Session::instance();
    
    if (isset($p->productid)) {
      $encid     = $this->ob_enc->encrypt($p->productid);
      $output    = "<b>{$p->productname}</b><br>{$p->price}<br>";
      $output   .= $this->htmlthumbnail($p, $encid);
      $output   .= $this->htmldetaillink($p, $encid);       
      $output   .= $this->htmlbasketlink($p, $encid);       
      
      if ($session->ifset('userid')) {
        $output .= $this->htmlfavorlink($p, $encid);
        $output .= $this->htmlstocklink($p, $encid);
      } // end is user login
    } else {
      $output = $p;
    }
    $this->rows[] = $output;
  }

  /**
   * add product detail to rows (dispatcher productdetail)
   * 
   * @param object $p The product
   */  
  public function addRowProductDetail(&$p) {

    $encid     = $this->ob_enc->encrypt($p->productid);
    $output    = "{$p->price}<br>";
    $output   .= $this->htmlthumbnail($p, $encid);
    $output   .= $this->htmlsharelink('product', $encid);
    $output   .= $this->htmlbasketlink($p, $encid);       

    $user =& ON_User::getInstance();

    if (false) {
      $output .= $this->htmlfavorlink($p, $encid);
      $output .= $this->htmlstocklink($p, $encid);
    } // end is user login    
    parent::addRow(array($output . $p->productdetail));
  }
    
  /**
   * return html of product
   * 
   */  
  public function displayProduct() {
    $c = count($this->rows);
    if ($c > 0) {
      // display the records 3 column in the web page
      for ($i = 0; $i<$c; $i=$i+2) {
        $tmp = array_slice($this->rows, $i, 2);
        parent::addRow($tmp);
      }
    }
    $this->setAllAttributes('valign="top"');
    $this->setRowAttributes(0, 'class="title"', true);
    if (isset($this->aColspan[0]) && $this->aColspan[0] > 0) { // title row
      $this->setCellAttributes(0, 0, 'colspan='.$this->aColspan[0]);
    }
    return $this->toHTML();
  }


}

?>