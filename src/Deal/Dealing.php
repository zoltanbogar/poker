<?php

namespace AzerionAssignment\Deal;

class Dealing implements DealingInterface {
  private $hand_array;

  public function getDealing(): DealingInterface {
    return $this;
  }

  public function getHands(): array {
    return $this->hand_array;
  }

  public function addHand(HandInterface $hand): void {
    $this->hand_array[] = $hand;
  }

  public function toString() {
    $string = "In this particular dealing the following hand(s) are present:\n";
    foreach($this->getHands() as $hand){
      $string .= $hand->toString() . "\n";
    }

    return $string;
  }
}