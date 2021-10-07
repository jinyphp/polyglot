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
    use Trans;
    
    /**
     * 인스턴스 생성
     */
    public static $Instance;
    public static function instance($path=null)
    {
        if (!isset(self::$Instance)) {              
            self::$Instance = new self();

            if ($path) {
                self::$Instance->setPath($path);
            } else {
                self::$Instance->initPath();
            }

            return self::$Instance;
        } else {
            // 인스턴스가 중복
            return self::$Instance; 
        }
    }


    public $_path;
    private $_sha = "sha3-512";
    

    private function getHistory($json, $language)
    {
        // 언어객체를 반환합니다.
        if (isset($json[$language])) {
            return $json[$language];
        } 
        
        return false;
    }

    private function makeTransText($str, $language)
    {
        // 파일에 번역 기록이 없는 경우,        
        $text = $this->trans($str, $language);
        $obj = $this->factory($text); // 새롭게 생성
            
            
        $json[$language]=[];
        array_unshift($json[$language], $obj); // 배열 앞에 추가

        // 저장합니다.
        $this->save($str, $json);

        return $json[$language];
    }



    /**
     * 문자열을 반환합니다.
     */
    public function echo($str, $language='str')
    {
        // 파일이 존재하는 경우
        if ($json = $this->load($str)) {
            $history = $this->getHistory($json, $language);
            if(!$history) {
                $history = $this->makeTransText($str, $language);
            }            
        } else {
            // 파일이 없는 경우, 새롭게 저장
            $history = $this->makeTransText($str, $language);
        }


        // 기록이 1개만 있는 경우
        if (is_object($history)) {
            return $history->text;
        } else
        if (is_array($history)) {
            if (empty($history)) {
                // 비어있는 배열일 경우 : 기록을 모두 삭제한 경우 발생...
                // 다시 생성
                $history = $this->makeTransText($str, $language);
            } 
            
            if(isset($history[0])) {
                if(is_object($history[0])) {
                    return $history[0]->text;
                } else
                if(is_array($history[0])) {
                    return $history[0]['text'];
                }
            }
        }

        return false;
    }



    public function factory($text)
    {
        return new \Jiny\Polyglot\Text($text);
    }

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
     * 리소스 파일을 삭제합니다.
     */
    public function remove($str)
    {
        // 파일경로와 파일명을 계산합니다.
        $filename = $this->filename($str);
        $key = $this->sha1_path($filename);
        $this->is_path($this->_path.DIRECTORY_SEPARATOR.$key);

        $filename = $this->_path.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$filename.".msg";

        if (file_exists($filename)) {
            // 파일이 존재하는 경우, 삭제함
            unlink($filename);
        }
    }

    /**
     * 
     */
}