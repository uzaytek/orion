<?php

/**
 * One line links end/begin position in the table
 *
 */ 
class ON_LinkForm {

  /**
   * link href=>name array
   *
   * @var array
   */ 
  private $arrLink = NULL;

  /**
   * link href=>name array
   *
   * @var array
   */ 
  public function __construct($arrLink) {
    $this->arrLink = $arrLink;
  }

  /**
   * return link
   *
   * @param integer $rowID database record id
   * @return array all arrLinks html
   */ 
  public function getForm($rowID) {
    $out = '';
    foreach ($this->arrLink as $href=>$text) {
      $out .= '<a href="' . $href . '&amp;id='.$rowID.'">'. $text. '</a> ';
    }
    return array($out);
  }

  /**
   * If display in the beginning return true
   *
   */ 
  public function isBegin() {
    return false;
  }

  /**
   * If display in the end return true
   *
   */ 
  public function isEnd() {
    return true;
  }


}

?>