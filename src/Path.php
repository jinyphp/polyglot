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

trait Path
{
    /**
     * 패스 경로를 초기화 합니다.
     */
    public function initPath()
    {
        // 기본 설정경로를 초기화 합니다.
        if (!is_dir("resource")) {
            mkdir("resource");
        }            

        if (!is_dir("resource/polyglot")) {
            mkdir("resource/polyglot");
        }

        $this->_path = "resource/polyglot";
    }

    /**
     * 경로를 설정합니다.
     */
    public function setPath($path)
    {
        $this->_path = $path;

        $this->is_path($path);
    }

    /**
     * 경로를 확인, 생성합니다.
     */
    public function is_path($path)
    {
        $p = explode(DIRECTORY_SEPARATOR, $path);
        $temp = "";
        foreach ($p as $dir) {
            $temp .= $dir;
            if (!is_dir($temp)) {
                mkdir($temp);
            }
            $temp .= DIRECTORY_SEPARATOR;
        }

        return true;
    }

    /**
     * sha 서브경로를 생성합니다.
     */
    public function sha1_path($sha)
    {
        $key = substr($sha,0,4).DIRECTORY_SEPARATOR;
        $key .= substr($sha,4,4).DIRECTORY_SEPARATOR;
        $key .= substr($sha,8,4);
        return $key;
    }

    /**
     * 
     */
}