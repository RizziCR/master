<?php

/**
 * Ets_Collection_Ressources
 * 
 * This class represents information on the amount of ressources.
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Collection_Ressources implements Iterator
{
  /**
   * Spelling for Iridium
   *
   * @var string
   */
  const IRIDIUM = 'Iridium';
  
  /**
   * Spelling for Holzium
   *
   * @var string
   */
  const HOLZIUM = 'Holzium';
  
  /**
   * Spelling for Wasser
   *
   * @var string
   */
  const WASSER  = 'Wasser';
  
  /**
   * Spelling for Sauerstoff
   *
   * @var string
   */
  const SAUERSTOFF = 'Sauerstoff';
  
  /**
   * Amount of Iridum
   *
   * @var integer
   */
  private $_iridium = 0;
  
  /**
   * Amount of Holzium
   *
   * @var integer
   */
  private $_holzium = 0;
  
  /**
   * Amount of Wasser
   *
   * @var integer
   */
  private $_wasser = 0;
  
  /**
   * Amount of Sauerstoff
   *
   * @var integer
   */
  private $_sauerstoff = 0;
  
  /**
   * Offset for interating this object
   *
   * @var integer
   */
  private $_offset = 0;
  
  /**
   * Creates new collection of ressources
   *
   * @param integer $iridium
   * @param integer $holzium
   * @param integer $wasser
   * @param integer $sauerstoff
   */
  public function __construct($iridium, $holzium, $wasser, $sauerstoff)
  {
    $this->_iridium = (int) $iridium;
    $this->_holzium = (int) $holzium;
    $this->_wasser = (int) $wasser;
    $this->_sauerstoff = (int) $sauerstoff;
  }
  
  /**
   * Rewinds iteration
   * 
   * @return void
   */
  public function rewind()
  {
    $this->_offset = 0;
  }
  
  /**
   * Gets value for current element
   *
   * @return integer
   */
  public function current()
  {
    switch($this->_offset) {
      case 0:
        return $this->_iridium;
        break;
      case 1:
        return $this->_holzium;
        break;
      case 2:
        return $this->_wasser;
        break;
      case 3:
        return $this->_sauerstoff;
        break;
    }
  }
  
  /**
   * Gets key for current element
   *
   * @return string
   */
  public function key()
  {
    switch($this->_offset) {
      case 0:
        return self::IRIDIUM;
        break;
      case 1:
        return self::HOLZIUM;
        break;
      case 2:
        return self::WASSER;
        break;
      case 3:
        return self::SAUERSTOFF;
        break;
    }
  }
  
  /**
   * Selects next element
   *
   * @return boolean
   */
  public function next()
  {
    $this->_offset ++;
    if ($this->_offset > 3 or $this->_offset < 0) return false;
    return true;
  }
  
  /**
   * Checks if iteration is not before or after the list
   *
   * @return boolean
   */
  public function valid()
  {
    if ($this->_offset > 3 or $this->_offset < 0) return false;
    return true;
  }
}