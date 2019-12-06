<?php

namespace AzerionAssignment;

use function array_keys;
use AzerionAssignment\Deal\Card;
use AzerionAssignment\Deal\Dealing;
use AzerionAssignment\Deal\Deck;
use AzerionAssignment\Deal\Evaluation;
use AzerionAssignment\Deal\Hand;
use AzerionAssignment\Exception\ConfigNotFoundException;
use AzerionAssignment\Exception\EmptyInputFileException;
use AzerionAssignment\File\FileContentValidator;
use AzerionAssignment\File\FileReader;

/**
 * Class Engine
 *
 * @package AzerionAssignment
 */
class Engine {
  /**
   * @var
   */
  private $input_file;
  /**
   * @var
   */
  private $input_array;
  /**
   * @var \AzerionAssignment\File\FileReader
   */
  private $file_reader;
  /**
   * @var \AzerionAssignment\Deal\Deck
   */
  private $deck;
  /**
   * @var
   */
  private $dealing;
  /**
   * @var
   */
  private $game_type;

  public function __construct($input_file, $game_type) {
    $this->input_file = $input_file;
    $this->file_reader = new FileReader();
    $this->deck = new Deck($game_type);
    $this->game_type = $game_type;
  }

  /**
   * @return string
   * @throws \AzerionAssignment\Exception\EmptyInputFileException
   * @throws \AzerionAssignment\Exception\InvalidHandException
   */
  public function run() {
    $this->input_array = $this->file_reader->readInput($this->input_file);

    if (!empty($this->input_array)) {
      $file_content_validator = new FileContentValidator();
      if ($file_content_validator->validateArray($this->input_array)) {
        $this->createDealing();

        $result = new Evaluation($this->dealing, $this->game_type);
        return $result->getResult();
      }
    } else {
      throw new EmptyInputFileException();
    }
  }

  /**
   * Creates the dealing from the input file
   */
  private function createDealing() {
    $this->dealing = new Dealing();

    foreach ($this->input_array as $hand) {
      $hand_array = explode(" ", trim($hand));
      $hand = $this->createHand($hand_array);
      $this->dealing->addHand($hand);
    }
  }

  /**
   * @param $hand_array
   *
   * @return \AzerionAssignment\Deal\Hand
   */
  private function createHand($hand_array) {
    $hand = new Hand();
    foreach ($hand_array as $card) {
      $card = $this->createCard($card);
      $hand->addCard($card);
    }
    return $hand;
  }

  /**
   * @param $card
   *
   * @return \AzerionAssignment\Deal\Card
   */
  private function createCard($card) {
    $rank_value_config = Util::createPlatformIndependentPath(__DIR__ . "/../config/RankValue.php");
    if (file_exists($rank_value_config)) {
      $rank_value_array = include $rank_value_config;
    } else {
      throw new ConfigNotFoundException();
    }

    $rank_array = array_keys($rank_value_array[$this->game_type]);

    foreach ($rank_array as $rank) {
      $rank = trim($rank);
      if (strpos($card, $rank) !== FALSE) {
        $suit = trim(str_replace($rank, "", $card));

        return new Card($suit, $rank);
      }
    }
  }
}