<?php

namespace AzerionAssignment\Deal;

/**
 * Class Card
 *
 * @package AzerionAssignment\Deal
 */
class Card implements CardInterface {
  /**
   * @var
   */
  private $rank;
  /**
   * @var
   */
  private $suit;

  /**
   * Card constructor.
   *
   * @param $suit
   * @param $rank
   */
  public function __construct($suit, $rank) {
    $this->suit = $suit;
    $this->rank = $rank;
  }

  /**
   * @return \AzerionAssignment\Deal\CardInterface
   */
  public function getCard(): CardInterface {
    return $this;
  }

  /**
   * @return string
   */
  public function getSuit(): string {
    return $this->suit;
  }

  /**
   * @return string
   */
  public function getRank(): string {
    return $this->rank;
  }

  /**
   * @return string
   */
  public function toString(): string {
    return "Suit: {$this->getSuit()}, and rank: {$this->getRank()}";
  }

  /**
   * @return array
   */
  public function getCardArray(): array {
    return [$this->suit, $this->rank];
  }
}