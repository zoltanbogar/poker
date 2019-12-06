<?php

namespace AzerionAssignment\Exception;

/**
 * Class InvalidHandException
 *
 * @package AzerionAssignment\Exception
 */
class InvalidHandException extends \Exception {
  /**
   * InvalidHandException constructor.
   *
   * @param $param
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($param, $message = '', $code = 0, \Exception $previous = null) {
    parent::__construct("Please add {$param} cards to each hand!", $code, $previous);
  }
}