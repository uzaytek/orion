<?php

require_once 'HTML/Table.php';

/**
 * Html table
 *
 */ 
class ON_Table extends HTML_Table
{

  /**
   * Quickform ojbect
   *
   * @var object
   */ 
  public $inline_form;  

  /**
   * Quickform ojbect
   *
   * @var object
   */ 
  public $block_form;  

  /**
   * Column count for colspan rows
   *
   * @var integer
   */ 
  public $colCount;

  /**
   * Construct table
   * 
   */  
  public function ON_Table($inline_form=null, $block_form=null) {
    parent::__construct('border=0 cellpadding=0 cellspacing=0 id="zebraTable"');
    $this->inline_form =& $inline_form;
    $this->block_form  =& $block_form;

  }

  /**
   * Add title row
   * 
   */  
  public function title($titles) {
    // inline_form column counts
    if ($this->inline_form) {
      $a = array_fill(0, 1, '');
      if($this->inline_form->isBegin()) { // inline_form html location
        $titles = array_merge($a,$titles);
      } else {
        $titles = array_merge($titles,$a); 
      }
    }
    $this->colCount = count($titles);
    // add titles
    $this->addRow($titles);
  }

  /**
   * Return colCount property
   * 
   * @return integer The colCount property
   */  
  public function colCount() {
    return (int)$this->colCount;
  }

  /**
   * Call parent addrow
   * 
   * @param mixed $row added row
   * @param integer $colspan Colspan
   * @return integer Added row id
   */  
  function addRow($row, $colspan=0) {
    if (!is_array($row)) {
      $row = array($row);
    }
    $col = parent::addRow($row);

    if ($colspan > 0) {
      $this->setCellAttributes($col, 0, 'colspan='.$colspan);
    }
    return $col;
  }

  /**
   * Add a row, add inline_form html begin or end to row
   * 
   */  
  public function tr($row, $row_id=0) {
    if ($this->inline_form) {
      $abegin = ($this->inline_form->isBegin()) ? $this->inline_form->getForm($row_id) : array();
      $aend   = ($this->inline_form->isEnd()) ? $this->inline_form->getForm($row_id) : array();    
      $this->addRow(array_merge($abegin, $row, $aend));
    } else {
      $this->addRow($row);
    }
  }

  /**
   * Display
   * 
   */  
  public function display() {
    $this->setRowAttributes(0, 'class="title"', true);
    $this->altRowAttributes(1, 'class="row"', 'class="altrow"', true);

    $output = $this->toHTML();
    // wrap output with form
    if ($this->block_form) {
      $output = $this->block_form->display($output);     
    }
    return $output;
  }
}

?>
