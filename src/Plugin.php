<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 5:32 PM
 */

namespace Pluginator;


class Plugin
{
    private $entryPoint;

    public function validate($dir) {
        if (!file_exists("$dir/plugin.php")) {
            return false;
        }
        return true;
    }

    public function load($dir) {
        $this->entryPoint = $dir.'/plugin.php';
    }

    public function isActive() {
        return true;
    }

    public function init() {
        if ($this->entryPoint === null) {
            throw new PluginatorException("Plugin must be loaded before use!");
        }

        if (!is_file($this->entryPoint)) {
            throw new PluginatorException("Plugin is damaged.");
        }

        include_once $this->entryPoint;
    }
}