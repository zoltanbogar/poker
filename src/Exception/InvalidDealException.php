<?php

namespace AzerionAssignment\Exception;

/**
 * Class InvalidDealException
 *
 * @package AzerionAssignment\Exception
 */
class InvalidDealException extends \Exception {
  /**
   * InvalidDealException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($message = '', $code = 0, \Exception $previous = null) {
    parent::__construct('Invalid deal!', $code, $previous);
  }
}