<?php

namespace Jiny\Polyglot;

class Text
{
    // 번역 문자열
    public $text;

    // 변경일자
    public $timestamp;

    // 작성자
    public $email;

    /**
     * 문자열을 초기화 합니다.
     */
    public function __construct($text=null)
    {
        $this->text = $text;
        $this->timestamp = time();
    }

    /**
     * 
     */
}