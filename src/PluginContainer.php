<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 5:27 PM
 */

namespace Pluginator;

use Traversable;

class PluginContainer implements \IteratorAggregate
{
    private $plugins = [];
    private $pluginsDir;

    public function __construct(string $pluginsDir = '/plugins/')
    {
        if (!file_exists($pluginsDir) || !is_dir($pluginsDir)) {
            throw new PluginatorException("$pluginsDir is not exists or not a directory");
        }

        $this->pluginsDir = $pluginsDir;
    }

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

    private function getDirectories($dir) {
        return glob($dir.'/*', GLOB_ONLYDIR);
    }

    public function loadPlugins() {
        $plugins = $this->scanPluginDir();

        foreach ($plugins as $plugin) {
            if ($plugin->isActive()) {
                $plugin->init();
            }
        }

        $this->plugins = $plugins;
    }

    public function getPlugins() {
        return $this->plugins;
    }

    /**
     * Retrieve an external iterator
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