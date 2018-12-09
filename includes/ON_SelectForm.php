<?php

/**
 * Select box inline forms for list pages
 *
 */ 
class ON_SelectForm {

  /**
   * Array item for select box
   *
   */ 
  private $arrSelect = NULL;

  /**
   * Database row id used for modify/delete/post another page
   *
   */ 
  private $identity;

  /**
   * Set array select and row element name pageid,newsid
   *
   */ 
  public function __construct($arrSelect, $identity='id') {
    $this->arrSelect = $arrSelect;
    $this->identity  = $identity;
  }

  /**
   * Return form html for row
   *
   */ 
  public function getForm($row_id) {

    $form = new ON_QuickForm('inline_'.$row_id);
    $form->setDivFormTemplate();

    $form->addElement('select', 'go', null, array(_('Select'))+$this->arrSelect, 
                      array('onblur'=>'changeaction(this.form,this.value)'));
    $form->setInlineTemplate('go');
    
    // row id and 
    $form->addElement('hidden', $this->identity, $row_id);

    // pagination value, lost if not saved
    if (isset($_REQUEST['currentpage'])) {
      $form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage'])); 
    }

    $form->addElement('submit', 'sb', _('Go'), 'class="si"');
    $form->setInlineTemplate('sb');    

    return array($form->toHtml());
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
