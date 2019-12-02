<?php

namespace AzerionAssignment\Deal;

use function array_keys;
use function array_search;
use function array_sum;
use function in_array;
use function var_dump;

class Evaluation
{
    private $dealing;
    private $game_type;

    public function __construct(Dealing $dealing, $game_type)
    {
        $this->dealing = $dealing;
        $this->game_type = $game_type;

        $this->processHands();
    }

    private function processHands()
    {
        foreach ($this->dealing->getHands() as $key => $hand) {
            $this->evaluateHand($hand);
            /*foreach($hand->getCards() as $card) {
                var_dump($card->getRank().$card->getSuit());

            }*/
        }
    }

    private function evaluateHand(Hand $hand)
    {
      $ranking = [
        'royal flush' => 1,
        'straight flush' => 2,
        'four of a kind' => 3,
        'full house' => 4,
        'flush' => 5,
        'straight' => 6,
        'three of a kind' => 7,
        'two pairs' => 8,
        'pair' => 9,
        'high card' => 10,
      ];

      $rank = 10;

      if($this->isRoyalFlush($hand)){
        $rank = 1;
      } else if($this->isStraightFlush($hand)){
        $rank = 2;
      } else if($this->isFourOfAKind($hand)){
        $rank = 3;
      }

      var_dump($rank);
    }

    public function isFourOfAKind($hand):bool{
      $cards = $hand->getHandArray();
      $result = [];
      foreach($cards as $card){
        $array_keys = array_keys($result);
        if(in_array($card[1], $array_keys)){
          $result[$card[1]]++;
        } else {
          $result[$card[1]] = 1;
        }
      }

      if(array_search(4, $result) !== false) {
        return true;
      }

      return false;
    }

    public function isStraightFlush($hand):bool{
      $cards = $hand->getHandArray();
      $first_card = $cards[0];
      $suit_of_first = $first_card[0];
      for($i = 1; $i < count($cards); $i++){
        if($suit_of_first !== $cards[$i][0]) {
          return false;
        }
      }

      $card_rank_array = [];

      foreach($cards as $card){
        $card_rank_array[] = $card[1];
      }

      $sum = array_sum($card_rank_array);
      $median = $sum / count($cards);
      //var_dump($sum / count($cards));
      if(!array_diff($card_rank_array, [$median-2, $median-1, $median, $median+1, $median+2])){
        return true;
      }

      return false;
    }

    public function isRoyalFlush($hand): bool{
      $cards = $hand->getHandArray();
      $first_card = $cards[0];
      $suit_of_first = $first_card[0];
      for($i = 1; $i < count($cards); $i++){
        if($suit_of_first !== $cards[$i][0]) {
          return false;
        }
      }

      $card_rank_array = [];

      foreach($cards as $card){
        $card_rank_array[] = $card[1];
      }

      if(!array_diff($card_rank_array, ['A', 'K', 'Q', 'J', '10'])){
        return true;
      }

      return false;
    }

    public function getResult()
    {
        return "bar";
    }
}