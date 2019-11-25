<?php

namespace AzerionAssignment;

use AzerionAssignment\File\FileReader;

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
  }

  private function validateRows() {

  }
}