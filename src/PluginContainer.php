<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 5:27 PM
 */

namespace Pluginator;

use Traversable;

/**
 * Class PluginContainer
 *
 * This is Traversable entity which is a container of all
 * the plugins installed. Please notice that it's not a container
 * of active plugins only. Every plugin installed is being loaded
 * in this list, no matter if it's active or not.
 *
 * Could be iterated for looping through all the plugins:
 * foreach ($pluginContainer as $plugin) {
 *      $plugin->load();
 * }
 *
 * @package Pluginator
 */
class PluginContainer implements \IteratorAggregate
{
    /**
     * List of all plugins
     * @var Plugin[]
     */
    private $plugins = [];

    /**
     * Plugins directory
     * @var string
     */
    private $pluginsDir;

    /**
     * PluginContainer constructor.
     * @param string $pluginsDir
     * @throws PluginatorException
     */
    public function __construct(string $pluginsDir = '/plugins/')
    {
        if (!file_exists($pluginsDir) || !is_dir($pluginsDir)) {
            throw new PluginatorException("$pluginsDir is not exists or not a directory");
        }

        $this->pluginsDir = $pluginsDir;
    }

    /**
     * Scans the plugins directory, and loads them.
     * This function does nothing with event handlers,
     * just returns the list of plugins intalled.
     * @return Plugins[]
     */
    private function scanPluginDir() {
        $dirs = $this->getDirectories($this->pluginsDir);

        $plugins = [];
        foreach ($dirs as $dir) {
            $plugin = new Plugin();
            if ($plugin->validate($dir)) {
                $plugin->load($dir);
                $plugins[] = $plugin;
            }
        }

        return $plugins;
    }

    /**
     * Gets the list of subdirectories of plugins dir.
     * @param $dir
     * @return array
     */
    private function getDirectories($dir) {
        return glob($dir.'/*', GLOB_ONLYDIR);
    }

    /**
     * Loads all the plugins installed.
     */
    public function loadPlugins() {
        EventHandler::clearEventMap();
        $plugins = $this->scanPluginDir();

        foreach ($plugins as $plugin) {
            if ($plugin->isActive()) {
                $plugin->init();
            }
        }

        $this->plugins = $plugins;
    }

    /**
     * Returns all the plugins loaded.
     * @return Plugin[]
     */
    public function getPlugins() {
        return $this->plugins;
    }

    /**
     * Retrieve an external iterator of all the plugins loaded.
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getPlugins());
    }
}