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
    static function init() {
        echo "Initialization process";
    }

    static function hook($name, $args) {
        echo "$name operation handler";
        print_r($args);
    }
}