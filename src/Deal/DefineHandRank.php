<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\EvaluationUtils;

/**
 * Class DefineHandRank
 *
 * @package AzerionAssignment\Deal
 */
class DefineHandRank {
  /**
   * @var \AzerionAssignment\EvaluationUtils
   */
  private $evaluation_utils;
  /**
   * @var
   */
  private $game_type;

  /**
   * DefineHandRank constructor.
   *
   * @param $game_type
   */
  public function __construct($game_type) {
    $this->evaluation_utils = new EvaluationUtils($game_type);
    $this->game_type = $game_type;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isPair(HandInterface $hand): bool {
    if (count(array_keys($this->evaluation_utils->createRankDistribution($hand), 2)) === 1) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isTwoPairs(HandInterface $hand): bool {
    if (count(array_keys($this->evaluation_utils->createRankDistribution($hand), 2)) === 2) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isThreeOfAKind(HandInterface $hand): bool {
    if (array_search(3, $this->evaluation_utils->createRankDistribution($hand)) !== FALSE) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function isStraight(HandInterface $hand): bool {
    $card_rank_array = $this->evaluation_utils->createCardArray($hand);

    $sum = array_sum($card_rank_array);
    $median = $sum / count($card_rank_array);

    if (floor($median) != $median) {
      $has_ace = in_array("A", $card_rank_array);
      $original_card_rank_array = $card_rank_array;

      $card_rank_array = $this->evaluation_utils->adjustRankInArray($card_rank_array);
      $sum = array_sum($card_rank_array);
      $median = $sum / count($card_rank_array);

      if ($has_ace && floor($median) != $median) {
        $card_rank_array = array_replace($original_card_rank_array,
          array_fill_keys(
            array_keys($original_card_rank_array, "A"),
            "A_low"
          )
        );
        $card_rank_array = $this->evaluation_utils->adjustRankInArray($card_rank_array);
        $sum = array_sum($card_rank_array);
        $median = $sum / count($card_rank_array);
      }
    }

    if (!array_diff($card_rank_array, [$median - 2, $median - 1, $median, $median + 1, $median + 2])) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isFlush(HandInterface $hand): bool {
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

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isFullHouse(HandInterface $hand): bool {
    if (array_search(3, $this->evaluation_utils->createRankDistribution($hand)) !== FALSE && array_search(2, $this->evaluation_utils->createRankDistribution($hand)) !== FALSE) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isFourOfAKind(HandInterface $hand): bool {
    if (array_search(4, $this->evaluation_utils->createRankDistribution($hand)) !== FALSE) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function isStraightFlush(HandInterface $hand): bool {
    if ($this->isFlush($hand) && $this->isStraight($hand)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return bool
   */
  public function isRoyalFlush(HandInterface $hand): bool {
    if (!array_diff($this->evaluation_utils->createCardArray($hand), ['A', 'K', 'Q', 'J', '10']) && $this->isFlush($hand)) {
      return TRUE;
    }

    return FALSE;
  }
}