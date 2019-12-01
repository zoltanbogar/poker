<?php

namespace AzerionAssignment\File;

use AzerionAssignment\Deal\Card;
use AzerionAssignment\Deal\Deck;

class DeckValidator
{
    private $deck;
    private $input_array;
    private $card_validator;

    public function __construct(Deck $deck, $input_array)
    {
        $this->deck = $deck;
        $this->input_array = $input_array;
        $this->card_validator = new CardValidator();

        $this->validateDeck();
    }

    private function validateDeck()
    {
        foreach($this->input_array as $hand){
            $hand_array = explode(" ", $hand);
            foreach($hand_array as $card) {
                $this->card_validator->validateCard($card);

                $rank_array = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2"];

                foreach ($rank_array as $rank) {
                    $rank = trim($rank);
                    if (strpos($card, $rank) !== FALSE) {
                        $suit = trim(str_replace($rank, "", $card));

                        $created_card = new Card($suit, $rank);
                        if(!$this->deck->removeCard($created_card)){
                            throw new \Exception("Invalid deal!");
                        }
                    }
                }
            }
        }
    }
}