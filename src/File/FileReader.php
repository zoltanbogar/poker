<?php

namespace AzerionAssignment\File;

use function is_dir;

class FileReader {
  private $input_array = [];

  public function readInput($input_file) : array {
    try {
      if(!is_readable($input_file) || is_dir($input_file)){
        throw new \Exception("File cannot be opened!");
      }

      $handle = fopen($input_file, "r");

      if ($handle) {
        while (($line = fgets($handle)) !== false) {
          $this->input_array[] = $line;
        }

        fclose($handle);
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
      exit;
    }

    return $this->input_array;
  }
}