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

    /**
     * 번역을 활성화 합니다.
     */
    public function enableTrans($keyfile)
    {
        if (file_exists($keyfile)) {
            // 외부에서 구글 키를 가지고 옵니다.
            $this->_apikey = include $keyfile;
        }

        return $this;
    }

    /**
     * 자동번역을 실행합니다.
     */
    public function trans($str, $lang)
    {
        // API 키가 존재할경우
        // Lang 코드가 "str" 아닌경우
        if ($this->_apikey && $lang != "str") {
            $google = new TranslateClient($this->_apikey);
            
            $result = $google->translate($str, [
                'target' => $lang
            ]);
            
            return $result['text']; 
        } else {
            // 번역을 할 수 없는 경우 입력값 반환
            return $str;
        }    
    }

    /**
     * 
     */
}