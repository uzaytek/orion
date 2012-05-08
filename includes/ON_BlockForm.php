<?php

/**
 * Wraps all rows with a form
 *
 */ 
class ON_BlockForm {

  /**
   * Quickform object
   *
   */ 
  public $form;

  /**
   * add a hidden go element for track
   *
   */ 
  public function __construct()  {
    $this->form = new ON_QuickForm('block');
    $this->form->setDivFormTemplate(); // set tableless form template
    $this->form->addElement('hidden', 'go', 'update'); 
  }

  /**
   * return text element string
   *
   */ 
  public function textElement($name, $val) {
    return '<input type="text" name="'.$name.'" value="'.$val.'" style="width:4em">';
  }

  /**
   * return submit element string
   *
   */ 
  public function submit($name, $val) {
    return '<input type="submit" name="'.$name.'" value="'.$val.'" classs="mi">';
  }

  /**
   * return form html
   *
   */ 
  public function display($output) {
    return $this->form->toHtml($output);
  }

}


?>