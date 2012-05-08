<?php

/**
 * Quemail dao object
 */
class ON_QueMail extends ON_Dao
{
  
  /**
   * Construct 
   */
  public function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_QUEMAIL, 'pk'=>'queid','seq'=>'_nid_seq'),
                        array('queid','mailto','mailsubject','mailbody','issent','dtcreated','extradata')
			);    
  }

  /**
   * Set quemail values
   * 
   * @param array $values The called function or user set
   */  
  public function setValues(&$values) {
    $this->mailto        = ON_Filter($values['mailto']);
    $this->mailsubject   = ON_Filter($values['mailsubject']);
    $this->mailbody      = ON_Filter($values['mailbody']);
    $this->extradata     = (isset($values['extradata'])) ? ON_Filter($values['extradata']) : null;
  }

  /**
   * Insert values
   *
   * @param integer $queid The database row id 
   */  
  public function insert(&$queid) {
    $this->issent    = 0;
    $this->dtcreated = $this->getDate('dt');
    return parent::insert($queid);
  }
}

?>