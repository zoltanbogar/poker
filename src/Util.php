<?php

namespace AzerionAssignment;

/**
 * Class Util
 *
 * @package AzerionAssignment
 */
class Util
{
  /**
   * @param $path
   *
   * @return mixed
   */
  public static function createPlatformIndependentPath($path){
        return str_replace(["\"", "/"], DIRECTORY_SEPARATOR, $path);
    }
}