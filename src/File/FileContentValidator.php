<?php

namespace AzerionAssignment\File;

class FileContentValidator {
  private $number_of_cards = 5;
  private $number_of_characters_per_card = [2, 5];

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
    //var_dump($length_of_card_string, $card, trim($card) === "A♠");
    if($length_of_card_string < $this->number_of_characters_per_card[0] || $length_of_card_string > $this->number_of_characters_per_card[1]) {
      var_dump("Incorrect card format!");
      exit;
    }

    if(!$card_suit = $this->checkRank($card)) {

    }
  }

  private function checkRank($card){
      //$chrs_card = str_split($card);
      $rank_card = NULL;
      $suit_card = NULL;
      $rank_array = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2", "1"];

      foreach($rank_array as $rank) {
          if (strpos($card, $rank) !== FALSE) {
              $rank_card = $rank;
              $suit_card = str_replace($rank, "", $card);
              break;
          }
      }
      var_dump($rank_card, $suit_card);
  }
}