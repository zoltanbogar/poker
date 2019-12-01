<?php

namespace AzerionAssignment\Deal;

interface DeckInterface
{
    public function createDeck():void;
    public function removeCard(Card $card):bool;
    public function addCard(Card $card):void;
    public function getCards():array;
}