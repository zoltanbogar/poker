<?php

namespace AzerionAssignment\Deal;

class Hand implements HandInterface {
  private $card_array;

  public function addCard(CardInterface $card): void {
    $this->card_array[] = $card;
  }

  public function getHand(): HandInterface {
    return $this;
  }

  public function getCards(): array {
    return $this->card_array;
  }

  public function toString(): string {
    $string = "You have ";
    foreach($this->getCards() as $card) {
      $string .= $card->toString() . " ";
    }

    return $string . "in your hand.";
  }
}