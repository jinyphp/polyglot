<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Polyglot;

use Google\Cloud\Translate\TranslateClient;

trait Trans
{
    private $_apikey;

    public function enableTrans($keyfile)
    {
        if (file_exists($keyfile)) {
            $this->_apikey = include $keyfile;
        }

        return $this;
    }

    /**
     * 자동번역을 실행합니다.
     */
    public function trans($str, $lang)
    {
        if ($this->_apikey) {
            $google = new TranslateClient($this->_apikey);
            
            $result = $google->translate($str, [
                'target' => $lang
            ]);
            
            return $result['text']; 
        } else {
            return $str;
        }    
    }

    /**
     * 
     */
}