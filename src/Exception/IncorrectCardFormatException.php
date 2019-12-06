<?php

namespace AzerionAssignment\Exception;

/**
 * Class IncorrectCardFormatException
 *
 * @package AzerionAssignment\Exception
 */
class IncorrectCardFormatException extends \Exception {
  /**
   * IncorrectCardFormatException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($message = '', $code = 0, \Exception $previous = null) {
    parent::__construct('Incorrect card format!', $code, $previous);
  }
}