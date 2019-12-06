<?php

namespace AzerionAssignment;

use AzerionAssignment\Deal\HandInterface;
use AzerionAssignment\Exception\ConfigNotFoundException;

/**
 * Class EvaluationUtils
 *
 * @package AzerionAssignment
 */
class EvaluationUtils {
  /**
   * @var
   */
  private $game_type;

  /**
   * EvaluationUtils constructor.
   *
   * @param $game_type
   */
  public function __construct($game_type) {
    $this->game_type = $game_type;
  }

  /**
   * @param $rank
   *
   * @return int
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function getRankValue($rank): int {
    $rank_value_config = Util::createPlatformIndependentPath(__DIR__ . "/../config/RankValue.php");
    if (file_exists($rank_value_config)) {
      $rank_value_array = include $rank_value_config;
    } else {
      throw new ConfigNotFoundException();
    }

    return $rank_value_array[$this->game_type][$rank];
  }

  /**
   * @param $card_rank_array
   *
   * @return array
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function adjustRankInArray($card_rank_array) {
    $result = [];
    foreach ($card_rank_array as $rank) {
      if ($rank == $this->getRankValue($rank)) {
        $result[] = $rank;
      } else {
        $result[] = $this->getRankValue($rank);
      }
    }

    return $result;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return array
   */
  public function createCardArray(HandInterface $hand): array {
    $cards = $hand->getHandArray();
    $card_rank_array = [];

    foreach ($cards as $card) {
      $card_rank_array[] = $card[1];
    }

    return $card_rank_array;
  }

  /**
   * @param \AzerionAssignment\Deal\HandInterface $hand
   *
   * @return array
   */
  public function createRankDistribution(HandInterface $hand): array {
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

  /**
   * @param $rank
   * @param $i
   * @param \AzerionAssignment\Deal\HandInterface $new_hand
   * @param $sorted_hands
   *
   * @return array
   */
  public function pushAndReindex($rank, $i, HandInterface $new_hand, &$sorted_hands) {
    $result = [];
    foreach ($sorted_hands[$rank] as $key => $hand) {
      if ($key === $i) {
        $result[$i] = $new_hand;
      }
      $result[] = $hand;
    }

    return $result;
  }
}