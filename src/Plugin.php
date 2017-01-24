<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 5:32 PM
 */

namespace Pluginator;

/**
 * Class Plugin
 *
 * Plugin entity. This class contains all the methods
 * needed to work with one concrete plugin.
 *
 * Example of usage:
 * if ($plugin->validate() && $plugin->isActive()) {
 *      $plugin->load();
 *      $plugin->init();
 * }
 *
 * @package Pluginator
 */
class Plugin
{
    /**
     * Entry point script. Should be provided after
     * the plugin loading.
     * @var string
     */
    private $entryPoint;

    /**
     * Performs a validation checks for a structure of the plugin.
     * Remember that it doesn't includes any code checks!
     *
     * @param string $dir
     * @return bool
     */
    public function validate($dir) {
        if (!file_exists("$dir/plugin.php")) {
            return false;
        }
        return true;
    }

    /**
     * Loads a plugin, so it's entry point will be bound.
     * Remember that it's better for you to consider to
     * check it first!
     *
     * Binding an event handlers is another method called init();
     *
     * E.g.
     * if ($plugin->validate()) {
     *      $plugin->load();
     *      $plugin->init();
     * }
     *
     * @param $dir
     */
    public function load($dir) {
        $this->entryPoint = $dir.'/plugin.php';
    }

    /**
     * @todo Checks if this plugin is enabled.
     * This functionality is not implemented yet.
     *
     * @return bool
     */
    public function isActive() {
        return true;
    }

    /**
     * Bounds all the events of this plugin to EventHandler.
     * Remember that you have to load() it first.
     *
     * @throws PluginatorException
     */
    public function init() {
        if ($this->entryPoint === null) {
            throw new PluginatorException("Plugin must be loaded before use!");
        }

        if (!is_file($this->entryPoint)) {
            throw new PluginatorException("Plugin entry point file is damaged.");
        }

        include_once $this->entryPoint;
    }
}