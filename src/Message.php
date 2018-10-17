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

class Message
{
    public $_path;

    use Path, SHA, Trans;

    /**
     * 초기화
     */
    public function __construct($path=null)
    {
        // echo __CLASS__;

        if ($path) {
            $this->setPath($path);
        } else {
            $this->initPath();
        }

        //$this->enableTrans("transkey.php");
    }

    /**
     * 문자열을 반환합니다.
     */
    public function echo($str, $language='str')
    {
        
        if ($json = $this->load($str)) {
            // 파일이 존재하는 경우
            if (isset($json[$language])) {
                $obj = $json[$language];

            } else {
                // 번역과정 처리
                // 객체로 저장함
                $text = $this->trans($str, $language);
                $obj = new \Jiny\Polyglot\Text($text);
                
                // 배열 앞에 추가
                $json[$language]=[];
                array_unshift($json[$language], $obj);

                $this->save($str, $json);

            }        
        } else {
            // 파일이 없는 경우, 새롭게 저장
            // $json['str'] = $str;
            
            // 객체로 저장함
            $text = $this->trans($str, $language);
            $obj = new \Jiny\Polyglot\Text($text);

            // 배열 앞에 추가
            $json[$language]=[];
            array_unshift($json[$language], $obj);

            $this->save($str, $json);

        }

        //print_r($obj);
        return $this->text0($obj);
    }

    /**
     * 객체에서 문자열을 반환합니다.
     */
    public function text0($obj)
    {
        if (is_object($obj)) {
            return $obj->text;
        }

        // 배열일때
        return $obj[0]['text'];
    }

    /**
     * 
     */
}