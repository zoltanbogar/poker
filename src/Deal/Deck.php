<?php

namespace AzerionAssignment\Deal;

use AzerionAssignment\Util;

class Deck implements DeckInterface
{
    private $array_cards;
    private $deck_type;

    public function __construct($deck_type)
    {
        $this->deck_type = $deck_type;
        $this->createDeck();
    }

    public function createDeck(): void
    {
        if($deck_config = Util::createPlatformIndependentPath(__DIR__ . "/../../config/Deck.php")) {
            $deck_array = include $deck_config;

            if($deck_array[$this->deck_type]) {
                $rank_array = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2"];

                foreach($deck_array[$this->deck_type] as $card){
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
        }
    }

    public function removeCard(Card $card): bool
    {
        foreach($this->array_cards as $key => $card_object) {
            if($card->getRank() === $card_object->getRank() && $card->getSuit() === $card_object->getSuit()){
                unset($this->array_cards[$key]);

                return true;
            }
        }

        return false;
    }

    public function addCard(Card $card): void
    {
        $this->array_cards[] = $card;
    }

    public function getCards(): array
    {
        return $this->array_cards;
    }


}