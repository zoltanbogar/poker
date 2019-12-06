<?php

namespace AzerionAssignment\Deal;

/**
 * Interface CardInterface
 *
 * @package AzerionAssignment\Deal
 */
interface CardInterface {
  /**
   * CardInterface constructor.
   *
   * @param $suit
   * @param $rank
   */
  public function __construct($suit, $rank);

  /**
   * @return \AzerionAssignment\Deal\CardInterface
   */
  public function getCard() : CardInterface;

  /**
   * @return string
   */
  public function getSuit() : string;

  /**
   * @return string
   */
  public function getRank() : string;

  /**
   * @return string
   */
  public function toString() : string;

  /**
   * @return array
   */
  public function getCardArray() : array;
}