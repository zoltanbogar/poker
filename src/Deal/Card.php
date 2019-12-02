<?php

namespace AzerionAssignment\Deal;

class Card implements CardInterface {
  private $rank;
  private $suit;

  public function __construct($suit, $rank) {
    $this->suit = $suit;
    $this->rank = $rank;
  }

  public function getCard(): CardInterface {
    return $this;
  }

  public function getSuit(): string {
    return $this->suit;
  }

  public function getRank(): string {
    return $this->rank;
  }

  public function toString(): string {
    return "Suit: {$this->getSuit()}, and rank: {$this->getRank()}";
  }

  public function getCardArray(): array {
    return [$this->suit, $this->rank];
  }
}