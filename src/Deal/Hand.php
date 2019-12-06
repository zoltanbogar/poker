<?php

namespace AzerionAssignment\Deal;

/**
 * Class Hand
 *
 * @package AzerionAssignment\Deal
 */
class Hand implements HandInterface {
  /**
   * @var
   */
  private $card_array;

  /**
   * @param \AzerionAssignment\Deal\CardInterface $card
   */
  public function addCard(CardInterface $card): void {
    $this->card_array[] = $card;
  }

  /**
   * @return \AzerionAssignment\Deal\HandInterface
   */
  public function getHand(): HandInterface {
    return $this;
  }

  /**
   * @return array
   */
  public function getCards(): array {
    return $this->card_array;
  }

  /**
   * @return string
   */
  public function toString(): string {
    $string = "You have ";
    foreach($this->getCards() as $card) {
      $string .= $card->toString() . " ";
    }

    return $string . "in your hand.";
  }

  /**
   * @return array
   */
  public function getHandArray(): array {
    $result = [];
    foreach($this->card_array as $card){
      $result[] = $card->getCardArray();
    }
    return $result;
  }
}