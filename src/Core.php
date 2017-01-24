<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * User: lehadnk
 * Date: 1/24/17
 * Time: 3:37 PM
 */

namespace Pluginator;

class Core
{
    /**
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

    static function init() {
        self::$pluginContainer = new PluginContainer(self::getConfig('pluginsDir'));
        self::$pluginContainer->loadPlugins();
    }

    static public function setConfig(array $config) {
        self::$config = $config;
    }

    static public function getConfig($var = null, $default = null) {
        if ($var === null) {
            return self::$config;
        }

        if (!isset(self::$config[$var])) {
            return $default;
        }

        return self::$config[$var];
    }

    static public function reloadPlugins() {
        self::$pluginContainer->loadPlugins();
    }
}