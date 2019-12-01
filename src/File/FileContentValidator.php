<?php

namespace AzerionAssignment\File;

class FileContentValidator
{
    private $number_of_cards = 5;

    public function validateArray($array_of_hands)
    {
        foreach ($array_of_hands as $key => $hand) {
            $hand_array = explode(" ", $hand);
            if (count($hand_array) != $this->number_of_cards) {
                throw new \Exception("Please add {$this->number_of_cards} cards to each hand!");
            }

            $this->processHand($hand_array);
        }

        return true;
    }

    private function processHand($hand_array)
    {
        foreach ($hand_array as $key => $card) {
            $card_validator = new CardValidator();
            $card_validator->validateCard($card);
        }
    }
}