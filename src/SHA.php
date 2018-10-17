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

trait SHA
{
    private $_sha = "sha3-512";
    
    /**
     * 문자열 해쉬 파일명을 생성합니다.
     */
    public function filename($str)
    {
        //return sha1($str);
        return hash($this->_sha, $str);
    }

    public function setSHA($_sha)
    {
        $this->_sha = $_sha;
    }

    /**
     * 문자열 파일저장
     */
    public function save($str, $json)
    {
        $filename = $this->filename($str);
        $key = $this->sha1_path($filename);
        $this->is_path($this->_path.DIRECTORY_SEPARATOR.$key);

        file_put_contents(
            $this->_path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$filename.".msg", 
            json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        return $str;
    }

    /**
     * 해쉬 문자열 데이터를 읽어 옵니다.
     */
    public function load($str)
    {
        // 파일경로와 파일명을 계산합니다.
        $filename = $this->filename($str);
        $key = $this->sha1_path($filename);
        $this->is_path($this->_path.DIRECTORY_SEPARATOR.$key);

        $filename = $this->_path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$filename.".msg";

        if (file_exists($filename)) {
            // 파일이 존재하는 경우
            $a = file_get_contents($filename);
            return json_decode($a, true); // 
        }
    }

    /**
     * 
     */
}