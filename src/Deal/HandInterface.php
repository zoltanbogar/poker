<?php

namespace AzerionAssignment\Deal;

/**
 * Interface HandInterface
 *
 * @package AzerionAssignment\Deal
 */
interface HandInterface {
  /**
   * @param \AzerionAssignment\Deal\CardInterface $card
   */
  public function addCard(CardInterface $card) : void;

  /**
   * @return \AzerionAssignment\Deal\HandInterface
   */
  public function getHand() : HandInterface;

  /**
   * @return array
   */
  public function getCards() : array;

  /**
   * @return string
   */
  public function toString() : string;

  /**
   * @return array
   */
  public function getHandArray() : array;
}