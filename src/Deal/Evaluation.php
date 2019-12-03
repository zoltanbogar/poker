<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\Util;
use function array_keys;
use function array_search;
use function array_sum;
use function in_array;
use function method_exists;
use function var_dump;

class Evaluation
{
    private $dealing;
    private $game_type;
    private $sorted_hands;

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
            //var_dump($this->sorted_hands[6]);
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

        if ($this->isRoyalFlush($hand)) {
            $rank = 1;
            $this->orderHand($hand, $rank);
        } else if ($this->isStraightFlush($hand)) {
            $rank = 2;
            $this->orderHand($hand, $rank);
        } else if ($this->isFourOfAKind($hand)) {
            $rank = 3;
            $this->orderHand($hand, $rank);
        } else if ($this->isFullHouse($hand)) {
            $rank = 4;
            $this->sorted_hands[$rank][] = $hand;
        } else if ($this->isFlush($hand)) {
            $rank = 5;
            $this->orderHand($hand, $rank);
        } else if ($this->isStraight($hand)) {
            $rank = 6;
            $this->orderHand($hand, $rank);
        } else if ($this->isThreeOfAKind($hand)) {
            $rank = 7;
            $this->orderHand($hand, $rank);
        } else if ($this->isTwoPairs($hand)) {
            $rank = 8;
            $this->sorted_hands[$rank][] = $hand;
        } else if ($this->isPair($hand)) {
            $rank = 9;
            $this->orderHand($hand, $rank);
        } else {
          $rank = 10;
          $this->orderHand($hand, $rank);
        }
    }

    public function compareHands($hand1, $hand2, $rank, $i)
    {
        switch ($rank) {
            case 2:
            case 6:
                $this->compareStraights($hand1, $hand2, $rank, $i);
                break;
            case 5:
                $this->compareFlushes($hand1, $hand2, $rank, $i);
                break;
            case 3:
                $this->compareSameRanks($hand1, $hand2, $rank, $i, 4);
                break;
            case 7:
                $this->compareSameRanks($hand1, $hand2, $rank, $i, 3);
                break;
            case 9:
                $this->compareSameRanks($hand1, $hand2, $rank, $i, 2);
                break;
        }
    }

    private function getRankValue($rank): int
    {
        if($rank_value_config = Util::createPlatformIndependentPath(__DIR__ . "/../../config/RankValue.php")) {
            $rank_value_array = include $rank_value_config;
        } else {
            throw new \Exception('Config not found');
        }

        return $rank_value_array[$rank];
    }

    private function compareSameRanks($hand1, $hand2, $rank, $i, $number_of_cards){
        //var_dump(array_search($number_of_cards, $this->createRankDistribution($hand1)) , array_search($number_of_cards, $this->createRankDistribution($hand2)));
        if(array_search($number_of_cards, $this->createRankDistribution($hand1)) > array_search($number_of_cards, $this->createRankDistribution($hand2))) {
          //var_dump($this->sorted_hands[$rank]);
            array_splice($this->sorted_hands[$rank], $i, 0, $hand1);
          //var_dump($this->sorted_hands[$rank]);
          //die("foo");
        }
    }

    private function compareStraights($hand1, $hand2, $rank, $i)
    {
        if(max($this->createCardArray($hand1)) > max($this->createCardArray($hand2))) {
            array_splice($this->sorted_hands[$rank], $i, 0, $hand1);
        }
    }

    private function compareFlushes($hand1, $hand2, $rank, $i)
    {
        $card_array_1 = $this->createCardArray($hand1);
        $card_array_2 = $this->createCardArray($hand2);
        sort($card_array_1);
        sort($card_array_2);

        for($j = 0; $j < count($card_array_1); $j++){
            if($card_array_1[$j] > $card_array_2[$j]){
                array_splice($this->sorted_hands[$rank], $i, 0, $hand1);
            }
        }
    }

    private function orderHand($hand, $rank): void
    {
        if ($rank === 1 || !isset($this->sorted_hands[$rank]) || empty($this->sorted_hands[$rank])) {
          //if($rank == 3) var_dump($this->sorted_hands[$rank]);
            $this->sorted_hands[$rank][] = $hand;
          //if($rank == 3) var_dump($this->sorted_hands[$rank]);
            //if($rank == 3) var_dump("1x",count($this->sorted_hands[$rank]));
        } else {

            //if($rank == 3) var_dump("hánysz0r",count($this->sorted_hands[$rank]));
            $count = count($this->sorted_hands[$rank]);
            for ($i = 0; $i < $count; $i++) {
                //if($rank == 3) var_dump($this->sorted_hands[$rank][$i]);
                $this->compareHands($hand, $this->sorted_hands[$rank][$i], $rank, $i);
            }
        }
    }

    public function isPair($hand): bool
    {
        if (count(array_keys($this->createRankDistribution($hand), 2)) === 1) {
            return true;
        }

        return false;
    }

    public function isTwoPairs($hand): bool
    {
        if (count(array_keys($this->createRankDistribution($hand), 2)) === 2) {
            return true;
        }

        return false;
    }

    public function isThreeOfAKind($hand): bool
    {
        if (array_search(3, $this->createRankDistribution($hand)) !== false) {
            return true;
        }

        return false;
    }

    public function isStraight($hand): bool
    {
        $card_rank_array = $this->createCardArray($hand);

        $sum = array_sum($card_rank_array);
        $median = $sum / count($card_rank_array);

        if (!array_diff($card_rank_array, [$median - 2, $median - 1, $median, $median + 1, $median + 2])) {
            return true;
        }

        return false;
    }

    public function isFlush($hand): bool
    {
        $cards = $hand->getHandArray();
        $first_card = $cards[0];
        $suit_of_first = $first_card[0];
        for ($i = 1; $i < count($cards); $i++) {
            if ($suit_of_first !== $cards[$i][0]) {
                return false;
            }
        }

        return true;
    }

    public function isFullHouse($hand): bool
    {
        if (array_search(3, $this->createRankDistribution($hand)) !== false && array_search(2, $this->createRankDistribution($hand)) !== false) {
            return true;
        }

        return false;
    }

    public function isFourOfAKind($hand): bool
    {
        if (array_search(4, $this->createRankDistribution($hand)) !== false) {
            return true;
        }

        return false;
    }

    public function isStraightFlush($hand): bool
    {
        if ($this->isFlush($hand) && $this->isStraight($hand)) {
            var_dump("sf");
            return true;
        }

        return false;
    }

    public function isRoyalFlush($hand): bool
    {
        if (!array_diff($this->createCardArray($hand), ['A', 'K', 'Q', 'J', '10']) && $this->isFlush($hand)) {
            return true;
        }

        return false;
    }

    private function createCardArray($hand): array
    {
        $cards = $hand->getHandArray();
        $card_rank_array = [];

        foreach ($cards as $card) {
            $card_rank_array[] = $card[1];
        }

        return $card_rank_array;
    }

    private function createRankDistribution($hand): array
    {
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

    public function getResult()
    {
        $result = []; //array print
        $result_string = ""; //string print
        ksort($this->sorted_hands); //string print
        foreach($this->sorted_hands as $rank => $hands_by_ranking){
          //$index = 0; //array print
            foreach($hands_by_ranking as $key => $hand){
              if(!method_exists($hand, "getCards")){
                foreach($hand as $card){
                  $result_string .= $card->getRank().$card->getSuit() . " "; //string print
                  //$result[$rank][$index][] = $card->getRank().$card->getSuit(); //array print
                }
              } else {
                foreach($hand->getCards() as $card){
                  $result_string .= $card->getRank().$card->getSuit() . " "; //string print
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