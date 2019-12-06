<?php

namespace AzerionAssignment\File;

use AzerionAssignment\Exception\IncorrectCardFormatException;
use AzerionAssignment\Exception\IncorrectRankException;
use AzerionAssignment\Exception\IncorrectSuitException;

/**
 * Class CardValidator
 *
 * @package AzerionAssignment\File
 */
class CardValidator
{
  /**
   * @var
   */
  private $card;
  /**
   * @var
   */
  private $rank;
  /**
   * @var
   */
  private $suit;
  /**
   * @var array
   */
  private $number_of_characters_per_card = [2, 5];

  /**
   * @param $card
   *
   * @throws \AzerionAssignment\Exception\IncorrectCardFormatException
   * @throws \AzerionAssignment\Exception\IncorrectRankException
   * @throws \AzerionAssignment\Exception\IncorrectSuitException
   */
  public function validateCard($card)
    {
        $this->card = $card;

        $length_of_card_string = strlen(trim($this->card));
        if ($length_of_card_string < $this->number_of_characters_per_card[0] || $length_of_card_string > $this->number_of_characters_per_card[1]) {
            throw new IncorrectCardFormatException();
        }

        if (!$this->checkRank()) {
            throw new IncorrectRankException();
        }

        if (!$this->checkSuit()) {
            throw new IncorrectSuitException();
        }
    }

  /**
   * @return bool
   */
  private function checkRank()
    {
        $rank_array = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2"];

        foreach ($rank_array as $rank) {
            $rank = trim($rank);
            if (strpos($this->card, $rank) !== FALSE) {
                $this->rank = $rank;
                $this->suit = trim(str_replace($rank, "", $this->card));

                return true;
            }
        }

        return false;
    }

  /**
   * @return bool
   */
  private function checkSuit()
    {
        $suit_array = ["♠", "♥", "♦", "♣"];

        if(in_array($this->suit, $suit_array)) {
            return true;
        }

        return false;
    }
}