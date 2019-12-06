<?php

namespace AzerionAssignment\File;

use AzerionAssignment\Exception\FileCannotBeOpenedException;

/**
 * Class FileReader
 *
 * @package AzerionAssignment\File
 */
class FileReader {
  /**
   * @var array
   */
  private $input_array = [];

  /**
   * @param $input_file
   *
   * @return array
   */
  public function readInput($input_file) : array {
    try {
      if(!is_readable($input_file) || is_dir($input_file)){
        throw new FileCannotBeOpenedException();
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