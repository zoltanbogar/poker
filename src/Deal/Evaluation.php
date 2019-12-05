<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\Util;
use function ctype_digit;
use function in_array;
use function is_array;

class Evaluation {
  private $dealing;
  private $game_type;
  private $sorted_hands;

  public function __construct(Dealing $dealing, $game_type) {
    $this->dealing = $dealing;
    $this->game_type = $game_type;

    $this->processHands();
  }

  private function processHands() {
    foreach ($this->dealing->getHands() as $key => $hand) {
      $this->evaluateHand($hand);
    }
  }

  private function evaluateHand(Hand $hand) {
    $ranking = [
      'royal flush'     => 1,
      'straight flush'  => 2,
      'four of a kind'  => 3,
      'full house'      => 4,
      'flush'           => 5,
      'straight'        => 6,
      'three of a kind' => 7,
      'two pairs'       => 8,
      'pair'            => 9,
      'high card'       => 10,
    ];

    if ($this->isRoyalFlush($hand)) {
      $rank = 1;
      $this->orderHand($hand, $rank);
    } elseif ($this->isStraightFlush($hand)) {
      $rank = 2;
      $this->orderHand($hand, $rank);
    } elseif ($this->isFourOfAKind($hand)) {
      $rank = 3;
      $this->orderHand($hand, $rank);
    } elseif ($this->isFullHouse($hand)) {
      $rank = 4;
      $this->orderHand($hand, $rank);
    } elseif ($this->isFlush($hand)) {
      $rank = 5;
      $this->orderHand($hand, $rank);
    } elseif ($this->isStraight($hand)) {
      $rank = 6;
      $this->orderHand($hand, $rank);
    } elseif ($this->isThreeOfAKind($hand)) {
      $rank = 7;
      $this->orderHand($hand, $rank);
    } elseif ($this->isTwoPairs($hand)) {
      $rank = 8;
      $this->orderHand($hand, $rank);
    } elseif ($this->isPair($hand)) {
      $rank = 9;
      $this->orderHand($hand, $rank);
    } else {
      $rank = 10;
      $this->orderHand($hand, $rank);
    }
  }

  public function compareHands($hand1, $hand2, $rank, $i) {
    switch ($rank) {
      case 2:
      case 6:
        return $this->compareStraights($hand1, $hand2, $rank, $i);
      case 5:
        return $this->compareFlushes($hand1, $hand2, $rank, $i);
      case 3:
        return $this->compareSameRanks($hand1, $hand2, $rank, $i, 4);
      case 7:
        return $this->compareSameRanks($hand1, $hand2, $rank, $i, 3);
      case 9:
        return $this->compareSameRanks($hand1, $hand2, $rank, $i, 2);
      case 4:
        return $this->compareSameRanks($hand1, $hand2, $rank, $i, 3);
      case 8:
        return $this->compareTwoPairs($hand1, $hand2, $rank, $i, 2);
      default:
        return $this->compareHighCards($hand1, $hand2, $rank, $i, 1);
    }
  }

  private function getRankValue($rank): int {
    if ($rank_value_config = Util::createPlatformIndependentPath(__DIR__ . "/../../config/RankValue.php")) {
      $rank_value_array = include $rank_value_config;
    } else {
      throw new \Exception('Config not found');
    }

    return $rank_value_array[$this->game_type][$rank];
  }

  public function compareHighCards($hand1, $hand2, $rank, $i, $number_of_cards){
    $hand1_cards = array_keys($this->createRankDistribution($hand1),$number_of_cards);
    $hand2_cards = array_keys($this->createRankDistribution($hand2),$number_of_cards);
    rsort($hand1_cards);
    rsort($hand2_cards);

    for($j = 0; $j < count($hand1_cards); $j++) {
      if($hand1_cards[$j] > $hand2_cards[$j]) {
        $this->pushAndReindex($rank, $i, $hand1);
        return true;
      }
    }

    return false;
  }

  public function compareTwoPairs($hand1, $hand2, $rank, $i, $number_of_cards):bool{
    $hand1_cards = array_keys($this->createRankDistribution($hand1),$number_of_cards);
    $hand2_cards = array_keys($this->createRankDistribution($hand2),$number_of_cards);
    if(max($hand1_cards) > max($hand2_cards) || (max($hand1_cards) == max($hand2_cards) && min($hand1_cards) > min($hand2_cards))){
      $this->pushAndReindex($rank, $i, $hand1);
      return true;
    } else if(max($hand1_cards) == max($hand2_cards) && min($hand1_cards) == min($hand2_cards)) {
      $hand1_kicker = array_search(1, $this->createRankDistribution($hand1));
      $hand2_kicker = array_search(1, $this->createRankDistribution($hand2));

      if($hand1_kicker > $hand2_kicker) {
        $this->pushAndReindex($rank, $i, $hand1);
        return true;
      }

      return false;
    }

    return false;
  }

  private function pushAndReindex($rank, $i, $new_hand){
    $result = [];
    foreach($this->sorted_hands[$rank] as $key => $hand){
      if($key === $i) {
        $result[$i] = $new_hand;
      }
        $result[] = $hand;
    }
    $this->sorted_hands[$rank] = $result;
  }

  private function compareSameRanks($hand1, $hand2, $rank, $i, $number_of_cards) {
    $hand1_poker = array_search($number_of_cards, $this->createRankDistribution($hand1));
    $hand2_poker = array_search($number_of_cards, $this->createRankDistribution($hand2));
    if ($hand1_poker > $hand2_poker) {
      $this->pushAndReindex($rank, $i, $hand1);
      return true;
    } else if($hand1_poker == $hand2_poker) {
      $hand1_kicker = array_search(1, $this->createRankDistribution($hand1));
      $hand2_kicker = array_search(1, $this->createRankDistribution($hand2));

      if($hand1_kicker > $hand2_kicker) {
        $this->pushAndReindex($rank, $i, $hand1);
        return true;
      }
    }

    return false;
  }

  private function compareStraights($hand1, $hand2, $rank, $i) {
    $card_rank_array1 = $this->createCardArray($hand1);
    $card_rank_array1 = $this->adjustRankInArray($card_rank_array1);
    $card_rank_array2 = $this->createCardArray($hand2);
    $card_rank_array2 = $this->adjustRankInArray($card_rank_array2);

    if (max($card_rank_array1) > max($card_rank_array2)) {
      $this->pushAndReindex($rank, $i, $hand1);
      return true;
    }
    return false;
  }

  private function compareFlushes($hand1, $hand2, $rank, $i) {
    $card_array_1 = $this->createCardArray($hand1);
    $card_array_2 = $this->createCardArray($hand2);
    sort($card_array_1);
    sort($card_array_2);

    for ($j = 0; $j < count($card_array_1); $j++) {
      if ($card_array_1[$j] > $card_array_2[$j]) {
        $this->pushAndReindex($rank, $i, $hand1);
        return true;
      }
    }
    return false;
  }

  private function orderHand($hand, $rank): void {
    if ($rank === 1 || !isset($this->sorted_hands[$rank]) || empty($this->sorted_hands[$rank])) {
      $this->sorted_hands[$rank][] = $hand;
    } else {
      $is_added = false;
      $count = count($this->sorted_hands[$rank]);
      for ($i = 0; $i < $count; $i++) {
        $is_added = $this->compareHands($hand, $this->sorted_hands[$rank][$i], $rank, $i);
        if($is_added) break;
      }
      if(!$is_added){
        $this->sorted_hands[$rank][] = $hand;
      }
    }
  }

  public function isPair($hand): bool {
    if (count(array_keys($this->createRankDistribution($hand), 2)) === 1) {
      return TRUE;
    }

    return FALSE;
  }

  public function isTwoPairs($hand): bool {
    if (count(array_keys($this->createRankDistribution($hand), 2)) === 2) {
      return TRUE;
    }

    return FALSE;
  }

  public function isThreeOfAKind($hand): bool {
    if (array_search(3, $this->createRankDistribution($hand)) !== FALSE) {
      return TRUE;
    }

    return FALSE;
  }

  public function isStraight($hand): bool {
    $card_rank_array = $this->createCardArray($hand);

    $sum = array_sum($card_rank_array);
    $median = $sum / count($card_rank_array);

    if(floor($median) != $median) {
      $has_ace =  in_array("A", $card_rank_array);
      $original_card_rank_array = $card_rank_array;

      $card_rank_array = $this->adjustRankInArray($card_rank_array);
      $sum = array_sum($card_rank_array);
      $median = $sum / count($card_rank_array);

      if($has_ace && floor($median) != $median) {
        $card_rank_array = array_replace($original_card_rank_array,
          array_fill_keys(
            array_keys($original_card_rank_array, "A"),
            "A_low"
          )
        );
        $card_rank_array = $this->adjustRankInArray($card_rank_array);
        $sum = array_sum($card_rank_array);
        $median = $sum / count($card_rank_array);
      }
    }

    if (!array_diff($card_rank_array, [$median - 2, $median - 1, $median, $median + 1, $median + 2])) {
      return TRUE;
    }

    return FALSE;
  }

  public function adjustRankInArray($card_rank_array){
    $result = [];
    foreach($card_rank_array as $rank){
      //var_dump($rank, $this->getRankValue($rank));
      if($rank == $this->getRankValue($rank)) {
        $result[] = $rank;
      } else {
        $result[] = $this->getRankValue($rank);
      }
    }
return $result;
  }

  public function isFlush($hand): bool {
    $cards = $hand->getHandArray();
    $first_card = $cards[0];
    $suit_of_first = $first_card[0];
    for ($i = 1; $i < count($cards); $i++) {
      if ($suit_of_first !== $cards[$i][0]) {
        return FALSE;
      }
    }

    return TRUE;
  }

  public function isFullHouse($hand): bool {
    if (array_search(3, $this->createRankDistribution($hand)) !== FALSE && array_search(2, $this->createRankDistribution($hand)) !== FALSE) {
      return TRUE;
    }

    return FALSE;
  }

  public function isFourOfAKind($hand): bool {
    if (array_search(4, $this->createRankDistribution($hand)) !== FALSE) {
      return TRUE;
    }

    return FALSE;
  }

  public function isStraightFlush($hand): bool {
    if ($this->isFlush($hand) && $this->isStraight($hand)) {
      return TRUE;
    }

    return FALSE;
  }

  public function isRoyalFlush($hand): bool {
    if (!array_diff($this->createCardArray($hand), ['A', 'K', 'Q', 'J', '10']) && $this->isFlush($hand)) {
      return TRUE;
    }

    return FALSE;
  }

  private function createCardArray($hand): array {
    $cards = $hand->getHandArray();
    $card_rank_array = [];

    foreach ($cards as $card) {
      $card_rank_array[] = $card[1];
    }

    return $card_rank_array;
  }

  private function createRankDistribution($hand): array {
    $cards = $hand->getHandArray();
    $result = [];
    foreach ($cards as $card) {
      $array_keys = array_keys($result);
      if (in_array($card[1], $array_keys)) {
        $result[$card[1]]++;
      } else {
        $result[$card[1]] = 1;
      }
    }
    return $result;
  }

  public function getResult() {
    $result = []; //array print
    $result_string = ""; //string print
    ksort($this->sorted_hands); //string print
    foreach ($this->sorted_hands as $rank => $hands_by_ranking) {
      //$index = 0; //array print
      foreach ($hands_by_ranking as $key => $hand) {
        if (!method_exists($hand, "getCards")) {
          foreach ($hand as $card) {
            $result_string .= $card->getRank() . $card->getSuit() . " "; //string print
            //$result[$rank][$index][] = $card->getRank().$card->getSuit(); //array print
          }
        } else {
          foreach ($hand->getCards() as $card) {
            $result_string .= $card->getRank() . $card->getSuit() . " "; //string print
            //$result[$rank][$index][] = $card->getRank().$card->getSuit(); //array print
          }
        }
        //$index++; //array print
        $result_string .= "\n"; //string print
      }
    }
    //ksort($result); //array print

    //return $result; //array print
    return $result_string; //string print
  }
}