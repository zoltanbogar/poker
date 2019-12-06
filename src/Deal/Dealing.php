<?php

namespace AzerionAssignment\Deal;

/**
 * Class Dealing
 *
 * @package AzerionAssignment\Deal
 */
class Dealing implements DealingInterface {
  /**
   * @var
   */
  private $hand_array;

  /**
   * @return \AzerionAssignment\Deal\DealingInterface
   */
  public function getDealing(): DealingInterface {
    return $this;
  }

  /**
   * @return array
   */
  public function getHands(): array {
    return $this->hand_array;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   */
  public function addHand(HandInterface $hand): void {
    $this->hand_array[] = $hand;
  }

  /**
   * @return mixed|string
   */
  public function toString() {
    $string = "In this particular dealing the following hand(s) are present:\n";
    foreach($this->getHands() as $hand){
      $string .= $hand->toString() . "\n";
    }

    return $string;
  }
}