<?php

namespace AzerionAssignment;

use AzerionAssignment\Deal\Card;
use AzerionAssignment\Deal\Dealing;
use AzerionAssignment\Deal\Hand;
use AzerionAssignment\File\FileContentValidator;
use AzerionAssignment\File\FileReader;
use function base64_decode;
use function explode;

class Engine {
  private $input_file;
  private $input_array;
  private $file_reader;

  public function __construct($input_file) {
    $this->input_file = $input_file;
    $this->file_reader = new FileReader();
  }

  public function run() {
    $this->input_array = $this->file_reader->readInput($this->input_file);

    if (!empty($this->input_array)) {
      $file_content_validator = new FileContentValidator();
      if ($file_content_validator->validateArray($this->input_array)) {
        $this->createDealing();
      }
    } else {
      var_dump("Empty input file");
      exit;
    }
  }

  private function createDealing() {
    $dealing = new Dealing();

    foreach ($this->input_array as $hand) {
      $hand_array = explode(" ", $hand);
      $hand = $this->createHand($hand_array);
      $dealing->addHand($hand);
    }

    //var_dump($dealing->getDealing());
    var_dump($dealing->toString());
  }

  private function createHand($hand_array) {
    $hand = new Hand();
    foreach($hand_array as $card){
      $card = $this->createCard($card);
      $hand->addCard($card);
    }
    return $hand;
  }

  private function createCard($card) {
    //var_dump($card);
    //die("asdsadsa");
    $card = new Card('red', '11');

    return $card;
  }
}