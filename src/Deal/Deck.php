<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\Exception\ConfigNotFoundException;
use AzerionAssignment\Util;

/**
 * Class Deck
 *
 * @package AzerionAssignment\Deal
 */
class Deck implements DeckInterface {
  /**
   * @var
   */
  private $array_cards;
  /**
   * @var
   */
  private $deck_type;

  /**
   * Deck constructor.
   *
   * @param $deck_type
   *
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function __construct($deck_type) {
    $this->deck_type = $deck_type;
    $this->createDeck();
  }

  /**
   * @throws \AzerionAssignment\Exception\ConfigNotFoundException
   */
  public function createDeck(): void {
    $deck_config = Util::createPlatformIndependentPath(__DIR__ . "/../../config/RankValue.php");
    if (file_exists($deck_config)) {
      $deck_array = include $deck_config;

      if ($deck_array[$this->deck_type]) {
        $rank_array = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2"];

        foreach ($deck_array[$this->deck_type] as $card) {
          foreach ($rank_array as $rank) {
            if (strpos($card, $rank) !== FALSE) {
              $suit = str_replace($rank, "", $card);

              $card = new Card($suit, $rank);
              $this->addCard($card);

              break;
            }
          }
        }
      }
    } else {
      throw new ConfigNotFoundException();
    }
  }

  /**
   * @param \AzerionAssignment\Deal\Card $card
   *
   * @return bool
   */
  public function removeCard(Card $card): bool {
    foreach ($this->array_cards as $key => $card_object) {
      if ($card->getRank() === $card_object->getRank() && $card->getSuit() === $card_object->getSuit()) {
        unset($this->array_cards[$key]);

        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * @param \AzerionAssignment\Deal\Card $card
   */
  public function addCard(Card $card): void {
    $this->array_cards[] = $card;
  }

  /**
   * @return array
   */
  public function getCards(): array {
    return $this->array_cards;
  }

}