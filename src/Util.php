<?php

namespace AzerionAssignment;

class Util
{
    public static function createPlatformIndependentPath($path){
        return str_replace(["\"", "/"], DIRECTORY_SEPARATOR, $path);
    }
}