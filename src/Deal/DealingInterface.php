<?php

namespace AzerionAssignment\Deal;

/**
 * Interface DealingInterface
 *
 * @package AzerionAssignment\Deal
 */
interface DealingInterface {
  /**
   * @return \AzerionAssignment\Deal\DealingInterface
   */
  public function getDealing() : DealingInterface;

  /**
   * @return array
   */
  public function getHands() : array;

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   */
  public function addHand(HandInterface $hand) : void;

  /**
   * @return mixed
   */
  public function toString();
}