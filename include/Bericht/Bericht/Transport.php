<?php


/**
 * Ets_Bericht_Transport
 * 
 * Class for transport reports
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Bericht_Transport extends Ets_Bericht_Abstract 
{
  /**
   * Ressources
   *
   * @var Ets_Collection_Ressources
   */
  private $_ressources = null;
  
  /**
   * Init
   *
   * @param array $params
   */
  protected function _init($params)
  {
    // read ressources
    if (!isset($params['ressources']) or !$params['ressources'] instanceof Ets_Collection_Ressources) {
      throw new Ets_Bericht_Exception('Ressources not specified or not of type Ets_Collection_Units.');
    }
    $this->_ressources = $params['ressources'];
    // read units
    if (!isset($params['units']) or !$params['units'] instanceof Ets_Collection_Units) {
      throw new Ets_Bericht_Exception('Units not specified or not of type Ets_Collection_Units.');
    }
    $this->_units = $params['units'];
    
    if(!isset($params['unit_success']) or !$params['unit_success']) {
    	$this->_success = 'fullhangar';
    } else {
    	$this->_success = 'success';
    }
  }
  
  /**
   * Generates XML
   *
   * @return string
   */
  protected function _generateInnerXml()
  {
    $r = '<transport type="ressources">';
    foreach($this->_ressources as $type => $amount) {
      $r .= $this->_generateRessourceXml($type, $amount);
    }
    $r .= '</transport>';
    
    $r .= '<transport type="fleet" success="'.$this->_success.'">';
    foreach($this->_units as $type => $data) {
      $r .= $this->_generateUnitXml($type, $data['sent'],$data['lost']);
    }
    $r .= '</transport>';
    
    return $r;
  }
}