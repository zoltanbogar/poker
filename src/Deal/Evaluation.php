<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\Exception\ConfigNotFoundException;
use AzerionAssignment\Util;

/**
 * Class Evaluation
 *
 * @package AzerionAssignment\Deal
 */
class Evaluation {
  /**
   * @var \AzerionAssignment\Deal\Dealing
   */
  private $dealing;
  /**
   * @var
   */
  private $game_type;
  /**
   * @var
   */
  private $sorted_hands;

  /**
   * Evaluation constructor.
   *
   * @param \AzerionAssignment\Deal\Dealing $dealing
   * @param $game_type
   */
  public function __construct(Dealing $dealing, $game_type) {
    $this->dealing = $dealing;
    $this->game_type = $game_type;

    $this->processHands();
  }

  /**
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  private function processHands() {
    foreach ($this->dealing->getHands() as $key => $hand) {
      $this->evaluateHand($hand);
    }
  }

  /**
   * @param \AzerionAssignment\Deal\Hand $hand
   *
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  private function evaluateHand(Hand $hand) {
    $hand_value_config = Util::createPlatformIndependentPath(__DIR__ . "/../../config/HandValue.php");
    if (file_exists($hand_value_config)) {
      $hand_value_array = include $hand_value_config;
    } else {
      throw new ConfigNotFoundException();
    }

    $hand_rank_definer = new DefineHandRank($this->game_type);

    if ($hand_rank_definer->isRoyalFlush($hand)) {
      $rank = $hand_value_array[$this->game_type]['royal flush'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isStraightFlush($hand)) {
      $rank = $hand_value_array[$this->game_type]['straight flush'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isFourOfAKind($hand)) {
      $rank = $hand_value_array[$this->game_type]['four of a kind'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isFullHouse($hand)) {
      $rank = $hand_value_array[$this->game_type]['full house'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isFlush($hand)) {
      $rank = $hand_value_array[$this->game_type]['flush'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isStraight($hand)) {
      $rank = $hand_value_array[$this->game_type]['straight'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isThreeOfAKind($hand)) {
      $rank = $hand_value_array[$this->game_type]['three of a kind'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isTwoPairs($hand)) {
      $rank = $hand_value_array[$this->game_type]['two pairs'];
      $this->orderHand($hand, $rank);
    } elseif ($hand_rank_definer->isPair($hand)) {
      $rank = $hand_value_array[$this->game_type]['pair'];
      $this->orderHand($hand, $rank);
    } else {
      $rank = $hand_value_array[$this->game_type]['high card'];
      $this->orderHand($hand, $rank);
    }
  }

  /**
   * @param $hand1
   * @param $hand2
   * @param $rank
   * @param $i
   *
   * @return bool
   */
  public function compareHands($hand1, $hand2, $rank, $i) {
    $comparer = new Compare($this->game_type, $this->sorted_hands);

    switch ($rank) {
      case 2:
      case 6:
        return $comparer->compareStraights($hand1, $hand2, $rank, $i);
      case 5:
        return $comparer->compareFlushes($hand1, $hand2, $rank, $i);
      case 3:
        return $comparer->compareSameRanks($hand1, $hand2, $rank, $i, 4);
      case 7:
        return $comparer->compareSameRanks($hand1, $hand2, $rank, $i, 3);
      case 9:
        return $comparer->compareSameRanks($hand1, $hand2, $rank, $i, 2);
      case 4:
        return $comparer->compareSameRanks($hand1, $hand2, $rank, $i, 3);
      case 8:
        return $comparer->compareTwoPairs($hand1, $hand2, $rank, $i, 2);
      default:
        return $comparer->compareHighCards($hand1, $hand2, $rank, $i, 1);
    }
  }

  /**
   * @param $hand
   * @param $rank
   */
  private function orderHand($hand, $rank): void {
    if ($rank === 1 || !isset($this->sorted_hands[$rank]) || empty($this->sorted_hands[$rank])) {
      $this->sorted_hands[$rank][] = $hand;
    } else {
      $is_added = FALSE;
      $count = count($this->sorted_hands[$rank]);
      for ($i = 0; $i < $count; $i++) {
        $is_added = $this->compareHands($hand, $this->sorted_hands[$rank][$i], $rank, $i);
        if ($is_added) {
          break;
        }
      }
      if (!$is_added) {
        $this->sorted_hands[$rank][] = $hand;
      }
    }
  }

  /**
   * @return string
   */
  public function getResult() {
    $result_string = "";
    ksort($this->sorted_hands);

    foreach ($this->sorted_hands as $rank => $hands_by_ranking) {
      foreach ($hands_by_ranking as $key => $hand) {
        if (!method_exists($hand, "getCards")) {
          foreach ($hand as $card) {
            $result_string .= $card->getRank() . $card->getSuit() . " ";
          }
        } else {
          foreach ($hand->getCards() as $card) {
            $result_string .= $card->getRank() . $card->getSuit() . " ";
          }
        }
        $result_string .= "\n";
      }
    }

    return $result_string;
  }
}