<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 6:37 PM
 */

class TestPlugin {
    public static function testStatic(&$a) {
        $a += 20;
    }

    public function testObject(&$a) {
        $a += 40;
    }
}

\Pluginator\EventHandler::bind('testStatic', 'TestPlugin::testStatic');

$plugin = new TestPlugin();
\Pluginator\EventHandler::bind('testObject', [$plugin, 'testObject']);