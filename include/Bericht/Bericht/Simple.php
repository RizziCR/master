<?php


/**
 * Ets_Bericht_Simple
 * 
 * With this class we can represent reports, which do not contain
 * any special information, except for some plaintext.
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Bericht_Simple extends Ets_Bericht_Abstract 
{
  /**
   * Some text that might occour in the report
   * 
   * @var string
   */
  private $_text = null;
  
  /**
   * Init
   *
   * @param array $params
   */
  protected function _init($params)
  {
    $this->_text = is_string($params) ? $params : '';
  }
  
  /**
   * Generates XML
   *
   * @return string
   */
  protected function _generateInnerXml()
  {
    $r = '<text>'
       . htmlspecialchars($this->_text)
       . '</text>';
    return $r;
  }
}