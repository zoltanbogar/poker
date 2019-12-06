<?php

namespace AzerionAssignment\Deal;

/**
 * Interface DeckInterface
 *
 * @package AzerionAssignment\Deal
 */
interface DeckInterface
{
  /**
   *
   */
  public function createDeck():void;

  /**
   * @param \AzerionAssignment\Deal\Card $card
   *
   * @return bool
   */
  public function removeCard(Card $card):bool;

  /**
   * @param \AzerionAssignment\Deal\Card $card
   */
  public function addCard(Card $card):void;

  /**
   * @return array
   */
  public function getCards():array;
}