<?php

namespace AzerionAssignment\Exception;

/**
 * Class IncorrectSuitException
 *
 * @package AzerionAssignment\Exception
 */
class IncorrectSuitException extends \Exception {
  /**
   * IncorrectSuitException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($message = '', $code = 0, \Exception $previous = null) {
    parent::__construct('Incorrect suit!', $code, $previous);
  }
}