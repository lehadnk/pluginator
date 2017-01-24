<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 6:22 PM
 */

namespace Pluginator;


class EventHandler
{
    private static $eventMap = [];

    public static function bind($event, callable $function) {
        self::$eventMap[$event][] = $function;
    }

    public static function trigger($event, $args = []) {
        if (!isset(self::$eventMap[$event])) {
            return;
        }

        foreach (self::$eventMap[$event] as $function) {
            call_user_func_array($function, $args);
        }
    }

    public static function clearEventMap() {
        self::$eventMap = [];
    }
}