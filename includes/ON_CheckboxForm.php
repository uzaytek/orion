<?php

/**
 * Check box inline forms for list pages
 *
 */ 
class ON_CheckBoxForm {

  /**
   * Construct
   *
   */ 
  public function __construct(){}

  /**
   * Set array checkbox element
   *
   */ 
  public function getForm($row_id) {
    return array('<input type="checkbox" name="ids['.$row_id.']" value=1>');
  }

  /**
   * If display in the beginning return true
   *
   */ 
  public function isBegin() {
    return true;
  }

  /**
   * If display in the end return true
   *
   */ 
  public function isEnd() {
    return false;
  }


}

?>