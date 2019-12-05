<?php

namespace AzerionAssignment;

use AzerionAssignment\Deal\Card;
use AzerionAssignment\Deal\Dealing;
use AzerionAssignment\Deal\Deck;
use AzerionAssignment\Deal\Evaluation;
use AzerionAssignment\Deal\Hand;
use AzerionAssignment\File\DeckValidator;
use AzerionAssignment\File\FileContentValidator;
use AzerionAssignment\File\FileReader;
use function base64_decode;
use function explode;

class Engine
{
    private $input_file;
    private $input_array;
    private $file_reader;
    private $deck;
    private $dealing;
    private $game_type;

    public function __construct($input_file, $game_type)
    {
        $this->input_file = $input_file;
        $this->file_reader = new FileReader();
        $this->deck = new Deck($game_type);
        $this->game_type = $game_type;
    }

    public function run()
    {
        $this->input_array = $this->file_reader->readInput($this->input_file);

        if (!empty($this->input_array)) {
            $file_content_validator = new FileContentValidator();
            if ($file_content_validator->validateArray($this->input_array)) {
                //new DeckValidator($this->deck, $this->input_array);
                $this->createDealing();

                $result = new Evaluation($this->dealing, $this->game_type);
                return $result->getResult();
            }
        } else {
            throw new \Exception("Empty input file");
        }
    }

    private function createDealing()
    {
        $this->dealing = new Dealing();

        foreach ($this->input_array as $hand) {
            $hand_array = explode(" ", $hand);
            $hand = $this->createHand($hand_array);
            $this->dealing->addHand($hand);
        }
    }

    private function createHand($hand_array)
    {
        $hand = new Hand();
        foreach ($hand_array as $card) {
            $card = $this->createCard($card);
            $hand->addCard($card);
        }
        return $hand;
    }

    private function createCard($card)
    {
        $rank_array = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2"];

        foreach ($rank_array as $rank) {
            $rank = trim($rank);
            if (strpos($card, $rank) !== FALSE) {
                $suit = trim(str_replace($rank, "", $card));

                $card = new Card($suit, $rank);

                return $card;
            }
        }
    }
}