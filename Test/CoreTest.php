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

    public function testInit() {
        Core::init();
    }
}
