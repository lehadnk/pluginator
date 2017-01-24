<?php

ini_set('xdebug.show_exception_trace', 0);

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Pluginator\Core;
use PHPUnit\Framework\TestCase;

/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * User: lehadnk
 * Date: 1/24/17
 * Time: 2:26 PM
 */
class CoreTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSetConfig() {
        Core::setConfig([
            'pluginsDir' => __DIR__.'/plugins/',
        ]);
    }

    public function testGetConfig() {
        $this->assertEquals(__DIR__.'/plugins/', Core::getConfig('pluginsDir'));
        $this->assertEquals(true, is_array(Core::getConfig()));
    }

    public function testInit() {
        Core::init();
    }

    public function testNonExisting() {
        \Pluginator\EventHandler::trigger('nonExisting');
    }

    public function testDoNothing() {
        \Pluginator\EventHandler::trigger('doNothing');
    }

    public function testClosure() {
        $a = 0;
        \Pluginator\EventHandler::trigger('testClosure', [&$a]);
        $this->assertEquals(5, $a);
    }

    public function testFunction() {
        $a = 0;
        \Pluginator\EventHandler::trigger('testFunction', [&$a]);
        $this->assertEquals(10, $a);
    }

    public function testStatic() {
        $a = 0;
        \Pluginator\EventHandler::trigger('testStatic', [&$a]);
        $this->assertEquals(20, $a);
    }

    public function testObject() {
        $a = 0;
        \Pluginator\EventHandler::trigger('testObject', [&$a]);
        $this->assertEquals(40, $a);
    }
}
