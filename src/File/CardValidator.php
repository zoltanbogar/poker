<?php

namespace AzerionAssignment\File;

class CardValidator
{
    private $card;
    private $rank;
    private $suit;
    private $number_of_characters_per_card = [2, 5];

    public function validateCard($card)
    {
        $this->card = $card;

        $length_of_card_string = strlen(trim($this->card));
        if ($length_of_card_string < $this->number_of_characters_per_card[0] || $length_of_card_string > $this->number_of_characters_per_card[1]) {
            throw new \Exception("Incorrect card format!");
        }

        if (!$this->checkRank()) {
            throw new \Exception("Incorrect rank!");
        }

        if (!$this->checkSuit()) {
            throw new \Exception("Incorrect suit!");
        }
    }

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

    private function checkSuit()
    {
        $suit_array = ["♠", "♥", "♦", "♣"];

        if(in_array($this->suit, $suit_array)) {
            return true;
        }

        return false;
    }
}