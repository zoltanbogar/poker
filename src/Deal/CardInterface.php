<?php

namespace AzerionAssignment\Deal;

interface CardInterface {
  public function __construct($suit, $rank);
  public function getCard() : CardInterface;
  public function getSuit() : string;
  public function getRank() : string;
  public function toString() : string;
}