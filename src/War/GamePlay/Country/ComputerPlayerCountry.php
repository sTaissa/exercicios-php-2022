<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country that is managed by the Computer.
 */
class ComputerPlayerCountry extends BaseCountry {

  /**
   * Choose one country to attack, or none.
   *
   * The computer may choose to attack or not. If it chooses not to attack,
   * return NULL. If it chooses to attack, return a neighbor to attack.
   *
   * It must NOT be a conquered country.
   * 
   * If the country has only one troop, it cannot attack, that troop must 
   * stay to defend the country.
   *
   * @return \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface|null
   *   The country that will be attacked, NULL if none will be.
   */
  public function chooseToAttack(): ?CountryInterface {
    if($this->getNumberOfTroops() == 1){
      return NULL;
    } else if(rand(0,2) == 0){
      return NULL;
    } else{
      $key = array_rand($this->neighbors);
      return $this->neighbors[$key];
    } 
  }
}
