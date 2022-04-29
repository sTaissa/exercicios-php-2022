<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country, that is also a player.
 */
class BaseCountry implements CountryInterface {

  /**
   * The name of the country.
   *
   * @var string
   */
  protected $name;

  /**
   * An array of countries that neighbor this country.
   *
   * @var array
   */
  protected $neighbors = [];

  /**
   * The number of troops this country has.
   *
   * @var int
   */
  protected $troops = 3;

  /**
   * Builder.
   *
   * @param string $name
   *   The name of the country.
   */
  public function __construct(string $name) {
    $this->name = $name;
  }

  /**
   * Gets the name of a country.
   *
   * @return string
   *   The name of the country.
   */
  public function getName(): string{
    return $this->name;
  }

  /**
   * Sets the neighbors of this country.
   *
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface[] $neighbors
   *   An array of countries that neighbor this country, indexed by their names.
   */
  public function setNeighbors(array $neighbors): void{
    $this->neighbors = $neighbors;
  }

  /**
   * Lists the neighbors of a country.
   *
   * @return \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface[]
   *   The country's neighbors.
   */
  public function getNeighbors(): array{
    return $this->neighbors;
  }

  /**
   * Returns how many troops there currently are in this country.
   *
   * @return int
   *   The number of troops this country has.
   */
  public function getNumberOfTroops(): int{
    return $this->troops;
  }

  /**
   * Determines whether the player has been conquered.
   *
   * When a country is conquered, its object is not destroyed but it will be
   * flagged as "conquered", so that the game manager knows it will no longer be
   * playing.
   *
   * @return bool
   *   If this country has been conquered by someone else, this method will
   *   return TRUE.
   */
  public function isConquered(): bool{
    if($this->troops <= 0){
      return TRUE;
    } else{
      return FALSE;
    }
  }

  /**
   * Called when, after a battle, the defending country end up with 0 troops.
   *
   * Register the neighbors of the conquered country as your own. Making sure there are no
   * duplicate countries in the array, nor the current country itself. 
   * 
   * As the country was conquered, it must no longer belong to the list of neighbors.
   * 
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $conqueredCountry
   *   The country that has just been conquered.
   */
  public function conquer(CountryInterface $conqueredCountry): void{
    $conqueredCountryNeighbors = $conqueredCountry->getNeighbors();

    foreach ($conqueredCountryNeighbors as $conqueredCountryNeighbor){
      if(!in_array($conqueredCountryNeighbor, $this->neighbors)){
        if($conqueredCountryNeighbor != $this->name){
          array_push($this->neighbors, $conqueredCountryNeighbor);
        }
      }
    }

    $key = array_search($conqueredCountry->getName(), $this->neighbors);
    unset($this->neighbors[$key]);
  }

  /**
   * Decreases the number of troops in this country by a given number.
   *
   * @param int $killedTroops
   *   The number of troops killed in battle.
   */
  public function killTroops(int $killedTroops): void{
    $this->troops = $this->troops - $killedTroops;
  }
}
