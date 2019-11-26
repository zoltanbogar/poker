<?php

namespace AzerionAssignment\File;

class FileContentValidator {
  private $number_of_cards = 5;
  private $number_of_characters_per_card = [2, 3];

  public function validateArray($array_of_hands) {
    foreach($array_of_hands as $key => $hand) {
      $hand_array = explode(" ", $hand);
      if(count($hand_array) != $this->number_of_cards) {
        var_dump("Please add {$this->number_of_cards} cards to each hand!");
        break;
      }

      $this->processHand($hand_array);
    }

    return true;
  }

  private function processHand($hand_array){
    foreach($hand_array as $key => $card){
      $this->validateCard($card);
    }
  }

  private function validateCard($card){
    $length_of_card_string = strlen(trim($card));
    if($length_of_card_string < $this->number_of_characters_per_card[0] || $length_of_card_string > $this->number_of_characters_per_card[1]) {
      var_dump($card, $length_of_card_string < $this->number_of_characters_per_card[0] , $length_of_card_string > $this->number_of_characters_per_card[1]);
      var_dump("Incorrect card format!");
      exit;
    }
  }
}