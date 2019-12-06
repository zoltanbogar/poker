<?php

namespace AzerionAssignment\Exception;

/**
 * Class FileCannotBeOpenedException
 *
 * @package AzerionAssignment\Exception
 */
class FileCannotBeOpenedException extends \Exception {
  /**
   * FileCannotBeOpenedException constructor.
   *
   * @param string $message
   * @param int $code
   * @param \Exception|NULL $previous
   */
  public function __construct($message = '', $code = 0, \Exception $previous = null) {
    parent::__construct('File cannot be opened!', $code, $previous);
  }
}