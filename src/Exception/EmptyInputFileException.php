<?php

namespace AzerionAssignment\Exception;

/**
 * Class EmptyInputFileException
 *
 * @package AzerionAssignment\Exception
 */
class EmptyInputFileException extends \Exception {
  /**
   * EmptyInputFileException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($message = '', $code = 0, \Exception $previous = null) {
    parent::__construct('Empty input file!', $code, $previous);
  }
}