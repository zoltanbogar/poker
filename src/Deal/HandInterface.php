<?php

namespace AzerionAssignment\Deal;

interface HandInterface {
  public function addCard(CardInterface $card) : void;
  public function getHand() : HandInterface;
  public function getCards() : array;
  public function toString() : string;
  public function getHandArray() : array;
}