<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\EvaluationUtils;

/**
 * Class Compare
 *
 * @package AzerionAssignment\Deal
 */
class Compare {
  /**
   * @var \AzerionAssignment\EvaluationUtils
   */
  private $evaluation_utils;
  /**
   * @var
   */
  private $game_type;
  /**
   * @var
   */
  private $sorted_hands;

  /**
   * Compare constructor.
   *
   * @param $game_type
   * @param $sorted_hands
   */
  public function __construct($game_type, &$sorted_hands) {
    $this->evaluation_utils = new EvaluationUtils($game_type);
    $this->game_type = $game_type;
    $this->sorted_hands = $sorted_hands;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand1
   * @param \AzerionAssignment\Deal\HandInterface $hand2
   * @param $rank
   * @param $i
   * @param $number_of_cards
   *
   * @return bool
   */
  public function compareHighCards(HandInterface $hand1, HandInterface $hand2, $rank, $i, $number_of_cards) {
    $hand1_cards = array_keys($this->evaluation_utils->createRankDistribution($hand1), $number_of_cards);
    $hand2_cards = array_keys($this->evaluation_utils->createRankDistribution($hand2), $number_of_cards);
    rsort($hand1_cards);
    rsort($hand2_cards);

    for ($j = 0; $j < count($hand1_cards); $j++) {
      if ($hand1_cards[$j] > $hand2_cards[$j]) {
        $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand1
   * @param \AzerionAssignment\Deal\HandInterface $hand2
   * @param $rank
   * @param $i
   * @param $number_of_cards
   *
   * @return bool
   */
  public function compareTwoPairs(HandInterface $hand1, HandInterface $hand2, $rank, $i, $number_of_cards): bool {
    $hand1_cards = array_keys($this->evaluation_utils->createRankDistribution($hand1), $number_of_cards);
    $hand2_cards = array_keys($this->evaluation_utils->createRankDistribution($hand2), $number_of_cards);
    if (max($hand1_cards) > max($hand2_cards) || (max($hand1_cards) == max($hand2_cards) && min($hand1_cards) > min($hand2_cards))) {
      $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
      return TRUE;
    } elseif (max($hand1_cards) == max($hand2_cards) && min($hand1_cards) == min($hand2_cards)) {
      $hand1_kicker = array_search(1, $this->evaluation_utils->createRankDistribution($hand1));
      $hand2_kicker = array_search(1, $this->evaluation_utils->createRankDistribution($hand2));

      if ($hand1_kicker > $hand2_kicker) {
        $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
        return TRUE;
      }

      return FALSE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand1
   * @param \AzerionAssignment\Deal\HandInterface $hand2
   * @param $rank
   * @param $i
   * @param $number_of_cards
   *
   * @return bool
   */
  public function compareSameRanks(HandInterface $hand1, HandInterface $hand2, $rank, $i, $number_of_cards) {
    $hand1_poker = array_search($number_of_cards, $this->evaluation_utils->createRankDistribution($hand1));
    $hand2_poker = array_search($number_of_cards, $this->evaluation_utils->createRankDistribution($hand2));
    if ($hand1_poker > $hand2_poker) {
      $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
      return TRUE;
    } elseif ($hand1_poker == $hand2_poker) {
      $hand1_kicker = array_search(1, $this->evaluation_utils->createRankDistribution($hand1));
      $hand2_kicker = array_search(1, $this->evaluation_utils->createRankDistribution($hand2));

      if ($hand1_kicker > $hand2_kicker) {
        $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand1
   * @param \AzerionAssignment\Deal\HandInterface $hand2
   * @param $rank
   * @param $i
   *
   * @return bool
   */
  public function compareFlushes(HandInterface $hand1, HandInterface $hand2, $rank, $i) {
    $card_array_1 = $this->evaluation_utils->createCardArray($hand1);
    $card_array_2 = $this->evaluation_utils->createCardArray($hand2);
    sort($card_array_1);
    sort($card_array_2);

    for ($j = 0; $j < count($card_array_1); $j++) {
      if ($card_array_1[$j] > $card_array_2[$j]) {
        $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand1
   * @param \AzerionAssignment\Deal\HandInterface $hand2
   * @param $rank
   * @param $i
   *
   * @return bool
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function compareStraights(HandInterface $hand1, HandInterface $hand2, $rank, $i) {
    $card_rank_array1 = $this->evaluation_utils->createCardArray($hand1);
    $card_rank_array1 = $this->evaluation_utils->adjustRankInArray($card_rank_array1);
    $card_rank_array2 = $this->evaluation_utils->createCardArray($hand2);
    $card_rank_array2 = $this->evaluation_utils->adjustRankInArray($card_rank_array2);

    if (max($card_rank_array1) > max($card_rank_array2)) {
      $this->sorted_hands = $this->evaluation_utils->pushAndReindex($rank, $i, $hand1, $this->sorted_hands);
      return TRUE;
    }
    return FALSE;
  }
}