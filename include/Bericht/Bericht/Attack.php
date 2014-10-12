<?php

/**
 * Ets_Bericht_Attack
 * 
 * Class for attack reports
 * 
 * This code was especially produced for "ETS 2021 e.V.", a
 * registered association that may use this code for any reason
 * it wants.
 * 
 * @author Dennis Riehle
 */
class Ets_Bericht_Attack extends Ets_Bericht_Abstract 
{
  /**
   * Amount of ressources that have been plundered
   *
   * @var Ets_Collection_Ressources
   */
  private $_ressources = null;
  
  /**
   * Collection of attacking units
   *
   * @var Ets_Collection_Units
   */
  private $_attacker = null;
  
  /**
   * Collection of defending units
   *
   * @var Ets_Collection_Units
   */
  private $_defender = null;
  /**
   * the points of defender
   *
   * @var integer
   */
  private $_defenderPoints = 0;
  
  /**
   * Init
   *
   * @param array $params
   */
  protected function _init($params)
  {
    // read ressources
    if (!isset($params['plunder']) or !$params['plunder'] instanceof Ets_Collection_Ressources) {
      throw new Ets_Bericht_Exception('Spyed ressources not specified or not of type Ets_Collection_Ressources.');
    }
    $this->_ressources = $params['plunder'];
    // read attacking units
    if (!isset($params['attacker']) or !$params['attacker'] instanceof Ets_Collection_Units) {
      throw new Ets_Bericht_Exception('Attacking units not specified or not of type Ets_Collection_Units.');
    }
    $this->_attacker = $params['attacker'];
    // read defending units
    if (!isset($params['defender']) or !$params['defender'] instanceof Ets_Collection_Units) {
      throw new Ets_Bericht_Exception('Defending units not specified or not of type Ets_Collection_Units.');
    }
    $this->_defender = $params['defender'];
    $this->_defenderPoints = $params['defenderPoints'];
  }
  
  /**
   * Generates XML
   *
   * @return string
   */
  protected function _generateInnerXml()
  {
    $r = '<attacker>';
    foreach($this->_attacker as $type => $data) {
      $r .= $this->_generateUnitXml($type, $data['sent'], $data['lost']);
    }
    $r .= '</attacker>'
       .  '<defender points="'.$this->_defenderPoints.'">';
    foreach($this->_defender as $type => $data) {
      $r .= $this->_generateUnitXml($type, $data['sent'], $data['lost']);
    }
    $r .= '</defender>'
       .  '<transport>';
    foreach($this->_ressources as $type => $amount) {
      $r .= $this->_generateRessourceXml($type, $amount);
    }
    $r .= '</transport>';
    return $r;
  }
}