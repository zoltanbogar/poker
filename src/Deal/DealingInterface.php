<?php

namespace AzerionAssignment\Deal;

interface DealingInterface {
  public function getDealing() : DealingInterface;
  public function getHands() : array;
  public function addHand(HandInterface $hand) : void;
  public function toString();
}