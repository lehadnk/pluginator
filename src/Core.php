<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * User: lehadnk
 * Date: 1/24/17
 * Time: 3:37 PM
 */

namespace Pluginator;

/**
 * Class Core
 *
 * Core class of the pluginator package.
 * Contains all the system operations.
 *
 * If you're looking for event binders and triggers,
 * you should take a look on EventHandler instead.
 * @see EventHandler
 * @package Pluginator
 */
class Core
{
    /**
     * Storage of all plugins loaded.
     * @var PluginContainer;
     */
    private static $pluginContainer;

    /**
     * Config array of the pluginator.
     * Should be assoc array.
     * @var array;
     */
    private static $config = [
        'pluginsDir' => '/plugins/',
    ];

    /**
     * Plugin system initialization method which prepares the system
     * to usage. This method (adding to setConfig which isn't mandatory
     * at this point) should be the only method user have to call
     * before using the plugins.
     */
    static function init() {
        self::$pluginContainer = new PluginContainer(self::getConfig('pluginsDir'));
        self::$pluginContainer->loadPlugins();
    }

    /**
     * Sets the config array of the pluginator.
     * Should be assoc array.
     *
     * Could be changed run-time, but remember
     * that you have to reload the plugins after!
     * @param array $config
     */
    static public function setConfig(array $config) {
        self::$config = $config;
    }

    /**
     * Gets the setting from the config loaded. Default value
     * could be provided and will be returned if no such section
     * found.
     * Use with no params for getting assoc array containing
     * all the settings.
     * @param string $var
     * @param any $default
     * @return any
     */
    static public function getConfig($var = null, $default = null) {
        if ($var === null) {
            return self::$config;
        }

        if (!array_key_exists($var, self::$config)) {
            return $default;
        }

        return self::$config[$var];
    }

    /**
     * Allows to reload plugins during the run-time.
     */
    static public function reloadPlugins() {
        self::$pluginContainer->loadPlugins();
    }
}