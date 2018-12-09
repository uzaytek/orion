<?php

/**
 * Statistics table dao class
 *
 */ 
class ON_Stat extends ON_Dao
{

  /**
   * Construct
   *
   */ 
  public function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_STATS),
                        array('itemid','stattype','useraction','dtcreated')
			);
  }

  /**
   * Set stat values
   * 
   * @param array $values statistics values comes from caller
   */  
  public function setValues(&$values) {
    $this->itemid     = (int)$values['itemid'];
    $this->stattype   = (int)$values['stattype'];
    $this->useraction = (int)$values['useraction'];
    $this->dtcreated  = $this->getDate('dt');
  }

  /**
   * Delete specified month
   * 
   * @param integer $month Delete month data from stat table
   * @return integer number of rows were affected
   */  
  public function delete($month) {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->exec('DELETE FROM'.$this->reg['table'].
                                ' WHERE EXTRACT(MONTH FROM s.dtcreated) = ' . $month);
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return $result;
  }

  /**
   * Return stat totals of user actions
   * 
   * @param integer $month requested month
   * @return PDOStatement object; resource of the executed query
   */  
  public function getUserActionStats($month) {
    $month = in_array($month,range(1,12)) ? ' AND MONTH(s.dtcreated) = '.$month : '';
    $where = '';

    if ($this->stattype == ON_STAT_PRODUCT) {
      $qry ='SELECT MAX(a.productname) AS productname, a.productid,'.
        ' SUM(CASE WHEN s.useraction='.ON_USER_ACTION_VIEW.' THEN 1 ELSE 0 END) AS ui,'.
        ' SUM(CASE WHEN s.useraction='.ON_ANON_ACTION_VIEW.' THEN 1 ELSE 0 END) AS ai,'.
        ' SUM(CASE WHEN s.useraction='.ON_USER_ACTION_SHARE.' THEN 1 ELSE 0 END) AS ut,'.
        ' SUM(CASE WHEN s.useraction='.ON_ANON_ACTION_SHARE.' THEN 1 ELSE 0 END) AS at'.
        ' FROM ' . DB_TBL_PRODUCTS . ' a,' . DB_TBL_STATS .' s'.
        ' WHERE a.productid=s.itemid ' . $where. $month .
        ' GROUP BY a.productid';
    } else if($this->stattype == ON_STAT_CATEGORY) {
      $qry ='SELECT MAX(a.cattitle) AS cattitle, a.catid,'.
        ' SUM(CASE WHEN s.useraction='.ON_USER_ACTION_VIEW.' THEN 1 ELSE 0 END) AS ui,'.
        ' SUM(CASE WHEN s.useraction='.ON_ANON_ACTION_VIEW.' THEN 1 ELSE 0 END) AS ai,'.
        ' SUM(CASE WHEN s.useraction='.ON_USER_ACTION_SHARE.' THEN 1 ELSE 0 END) AS ut,'.
        ' SUM(CASE WHEN s.useraction='.ON_ANON_ACTION_SHARE.' THEN 1 ELSE 0 END) AS at'.
        ' FROM ' . DB_TBL_CATS . ' a,' . DB_TBL_STATS .' s'.
        ' WHERE a.catid=s.itemid ' . $where .$month .
        ' GROUP BY a.catid';
    } else {
      return;
    }

    try {
      if(!self::$db) $this->connect();
      return self::$db->query($qry);
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }      
  }
}


?>