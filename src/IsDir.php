<?php

namespace Mydom\Uploads;

class IsDir
{
    public $path;
    public $childPath;

    public function __construct($childPath)
    {
        if (!$childPath) {
            throw new \Exception('请传入指定目录');
        }

        $this->path = getcwd() . '\\';
        if (strstr($this->path, 'public') === false) {
            $this->childPath = 'public/' . $childPath;
        } else {
            $this->childPath = '/' . $childPath;
        }
        $this->isdir();
    }

    protected function isdir()
    {

        //循环检测目录是否存在，没有则创建
        $pathArr = explode('/', $this->childPath);

        foreach ($pathArr as $ml) {
            if (!$ml) continue;
            $this->path .= $ml . '\\';

            if (!is_dir($this->path)) {
                mkdir($this->path);
            }
        }
    }
}