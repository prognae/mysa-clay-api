<?php

namespace App\Helpers;

class RoleHelper
{
    public static $roles = [
        1 => 'admin',
        2 => 'customer',
    ];
    
    static function getName($key)
    {
        try {    
            return self::$roles[$key];
        } catch (\Exception $e) {
           \Log::info($e);
        }
    }

    static function getKey($key)
    {
        try {
            $roles = array_flip(self::$roles);
    
            return $roles[$key];
        } catch (\Exception $e) {
           \Log::info($e);
        }
    }
}