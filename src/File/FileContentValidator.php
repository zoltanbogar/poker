<?php

namespace AzerionAssignment\File;

use AzerionAssignment\Exception\InvalidHandException;

/**
 * Class FileContentValidator
 *
 * @package AzerionAssignment\File
 */
class FileContentValidator
{
  /**
   * @var int
   */
  private $number_of_cards = 5;

  /**
   * @param $array_of_hands
   *
   * @return bool
   * @throws \AzerionAssignment\Exception\InvalidHandException
   */
  public function validateArray($array_of_hands)
    {
        foreach ($array_of_hands as $key => $hand) {
            $hand_array = explode(" ", $hand);
            if (count($hand_array) != $this->number_of_cards) {
                throw new InvalidHandException($this->number_of_cards);
            }

            $this->processHand($hand_array);
        }

        return true;
    }

  /**
   * @param $hand_array
   *
   * @throws \AzerionAssignment\Exception\IncorrectCardFormatException
   * @throws \AzerionAssignment\Exception\IncorrectRankException
   * @throws \AzerionAssignment\Exception\IncorrectSuitException
   */
  private function processHand($hand_array)
    {
        foreach ($hand_array as $key => $card) {
            $card_validator = new CardValidator();
            $card_validator->validateCard($card);
        }
    }
}