<?php

namespace AzerionAssignment\Deal;

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

    }

    public function getResult()
    {
        return "bar";
    }
}