<?php
/**
 * Author: Zauzin Alexey <lehadnk@gmail.com>
 * Date: 1/24/17
 * Time: 6:37 PM
 */

$a = 5;
\Pluginator\EventHandler::bind('testClosure', function (&$b) use ($a) {
    $b += $a;
});

\Pluginator\EventHandler::bind('testFunction', function (&$b) {
    $b += 10;
});