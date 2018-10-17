<?php

/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (! function_exists('msg_init')) {
    function _msg_init($path=null)
    {
        $obj = new \Jiny\Polyglot\Message($path);
        return $obj;
    }
}