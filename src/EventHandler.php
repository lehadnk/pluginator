<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 6:22 PM
 */

namespace Pluginator;

/**
 * Class EventHandler
 *
 * Primary class for binding and triggering the events.
 * Generally, main class for plugin developers, since the
 * only entry point they require is contained on here:
 *
 * \Pluginator\EventHandler::bind('changedSettings', function($newSettings) {
 *      doSomethingWithThem($settings);
 * });
 *
 * Also, along with Core it's one of two entry points for
 * system developers:
 *
 * EventHandler::trigger('changedSettings', [&$settings]);
 *
 * @package Pluginator
 */
class EventHandler
{
    /**
     * Event map. Contains all the events bound right now.
     * @var callable[]
     */
    private static $eventMap = [];

    /**
     * Allows to bind the function to some event.
     * Event name is just a string.
     * Function is callable, so it could be anything from
     * function/anonymous function/closure/static function/object.
     *
     * E.g:
     * // Anonymous function
     * EventHandler::bind('anonymousFunction', function($a, $b) {
     *      doSomething($a, $b);
     * });
     *
     * function myFunc() {doSomething();}
     * // myFunc();
     * EventHandler::bind('function', 'myFunc');
     *
     * // Class::staticMethod();
     * EventHandler::bind('staticCall', 'Class::staticMethod');
     *
     * // $object->doSomething();
     * EventHandler::bind('Object function', [$object, 'doSomething']);
     *
     * @param string $event
     * @param callable $function
     */
    public static function bind($event, callable $function) {
        self::$eventMap[$event][] = $function;
    }

    /**
     * This function triggers the event, so every plugin
     * bound to it will be fired. This trigger could contain
     * an arguments, including ones which could be passed by
     * reference, so plugin developer could modify it.
     *
     * Arguments are always provided as an array, even if
     * there's only one of them.
     *
     * E.g.:
     * EventHandler::trigger('addToCart', [$cart, $item]);
     * EventHandler::trigger('itemAddedToCart', [$item]);
     *
     * @param string $event
     * @param array $args
     */
    public static function trigger($event, array $args = []) {
        if (!isset(self::$eventMap[$event])) {
            return;
        }

        foreach (self::$eventMap[$event] as $function) {
            call_user_func_array($function, $args);
        }
    }

    /**
     * Clears all the entire event map.
     * Use with caution! Remember that you'll have to reload
     * the plugins to get them to work again.
     */
    public static function clearEventMap() {
        self::$eventMap = [];
    }
}