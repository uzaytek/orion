<?php

/**
 * Log table dao object 
 * 
 */  
class ON_Log extends ON_Dao
{

  /**
   * Construct logs
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_LOGS, 'pk'=>'logid','seq'=>'_nid_seq'),
                        array('logid','logvalue','dtcreated')
                        );
  }

  /**
   * Insert values to db
   * 
   * @param array $values The log values
   * @param integer $theid The database row id
   */  
  public function insert(&$value, $insertid=0) {
    $this->dtcreated  = $this->getDate('il');
    $this->setValues($value);
    return parent::insert($insertid);
  }

  /**
   * Set log values
   * 
   * @param array $values The log values
   */  
  public function setValues(&$value) {
    $this->logvalue   = $_SERVER['REQUEST_TIME'] . ":". $_SERVER['SCRIPT_NAME'] . ":". 
      ON_Filter($_SERVER['QUERY_STRING']) . ':' . ON_Filter($value);
  }
}


?>