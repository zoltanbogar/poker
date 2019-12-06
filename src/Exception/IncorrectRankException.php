<?php

namespace AzerionAssignment\Exception;

/**
 * Class IncorrectRankException
 *
 * @package AzerionAssignment\Exception
 */
class IncorrectRankException extends \Exception {
  /**
   * IncorrectRankException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($message = '', $code = 0, \Exception $previous = null) {
    parent::__construct('Incorrect rank!', $code, $previous);
  }
}