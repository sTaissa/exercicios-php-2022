<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay;

use Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface;

/**
 * A manager that will roll the dice and compute the winners of a battle.
 */
class Battlefield implements BattlefieldInterface {

  /**
   * Rolls the dice for a country.
   *
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $country
   *   The country that is rolling the dice.
   * @param bool $isAtacking
   *   TRUE if the dice is being rolled by the attacker, FALSE if by the
   *   defender.
   *
   * @return int[]
   *   An array with values from 1-to-6. The array must have as many items as:
   *     - the number of troops of the country, when the defender is rolling
   *       the dice.
   *     - the number of troops of the country MINUS ONE, when the attacker is
   *       the one rolling the dice.
   */
  public function rollDice(CountryInterface $country, bool $isAtacking): array{
    $numberOfTroops = $country->getNumberOfTroops();
    $dices = [];

    if($isAtacking){
        $numberOfTroops--;
    }

    for($i = 0; $i < $numberOfTroops; $i++){
        array_push($dices, rand(1,6));
    }

    rsort($dices);

    return $dices;
  }

  /**
   * Computes the winners and losers of a battle.
   *
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $attackingCountry
   *   The country that is attacking.
   * @param int[] $attackingDice
   *   The number
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $defendingCountry
   *   The country that is defending from the attack.
   * @param int[] $defendingDice
   *   The number
   */

  public function computeBattle(CountryInterface $attackingCountry, array $attackingDice, CountryInterface $defendingCountry, array $defendingDice): void{
    $attackingDiceCount = count($attackingDice);
    $defendingDiceCount = count($defendingDice);
    $attackingCountryDefeats = 0;
    $defendingCountryDefeats = 0;

    if($attackingDiceCount < $defendingDiceCount){
        $smallestDice = $attackingDiceCount;
    } else{
        $smallestDice = $defendingDiceCount;
    }

    for($i = 0; $i < $smallestDice; $i++){
        if($attackingDice[$i] <= $defendingDice[$i]){
            $attackingCountryDefeats++;
        } else{
            $defendingCountryDefeats++;
        }
    }

    $attackingCountry->killTroops($attackingCountryDefeats);
    $defendingCountry->killTroops($defendingCountryDefeats);
  }
}
