<?php

namespace Mydom\Uploads;


class Signature extends IsDir
{
    /**
     * git tag -a v1.0.2 -m "第二个版本"
     * git push origin v1.0.2
     */
    private $hard_ext = 'webp';
    private $imageExt = ['jpg', 'png', 'jpeg', 'PNG', 'JPG', 'JPEG','webp'];
    private $videoExt = ['MP4', 'mp4', 'ogg', 'avi', 'wmv', 'mov'];
    private $Ext = [];
    private $key = '9a018cfc8e4b9de4a5f6f8dd89d5fb66';
    private $iv = 'EE67C9C956215241';
    public $default_img = '';

    /**
     * @param string $childPath 指定上传目录
     * @param string $formats 上传类型 图片=imgage,视频=video
     * @param array $assignext 允许上传的文件后缀
     * @param string $file_ext 指定上传文件后缀
     * @param string $settingImg 404图片
     */
    public function __construct(string $childPath = 'uploads',string $formats = 'images',array $assignext = [],string $file_ext = null,string $settingImg = null)
    {
        if($settingImg){
            $this->default_img = $settingImg;
        }
        //指定文件后缀
        if($file_ext){
            $this->hard_ext = $file_ext;
        }
        if($assignext){
            $this->Ext = array_merge($this->Ext,$assignext);
        }
        if($formats == 'images'){
            $this->Ext = $this->imageExt;
        }else if($formats == 'video'){
            $this->Ext = $this->videoExt;
        }
        parent::__construct($childPath);
    }

    /**
     * 加密上传
     */
    public function encryptUpload($files,$is_jm = false)
    {
        if(empty($files)){
            throw new \Exception('请上传文件',0);
        }
        $savename = [];
        foreach ($files as $file) {
            $ext = $this->getExt($file['name']);
            //上传的是图片则数组中不能包含其它格式文件
            if (!in_array($ext, $this->Ext)) {
                throw new \Exception('不支持的格式上传',0);
            }
            //文件名称
            $filename = md5($file['name'].rand(10000,9999999)) . '-' . date('YmdHis') . '.' . $this->hard_ext;
            //文件指定位置
            $newPath = $this->path.$filename;
            //加密图片
            $tmpName = $file['tmp_name'];
            $this->encryPath($tmpName,$newPath);
            if($is_jm) {
                //解密图片
                $savename['imgPath'][] = $this->childPath . '/' . $filename;
                $savename['img'][] = $this->decryptPath($newPath, $this->hard_ext);
            }
        }
        if($is_jm) {
            return $savename;
        }else{
            return ['code' => 200,'msg' => '上传成功'];
        }
    }

    /**
     * 获取文件后缀
     * @param $filename
     * @return mixed
     */
    private function getExt($filename)
    {
        $arr = pathinfo($filename);
        $ext = $arr['extension'];
        return $ext;
    }

    /**
     * 文件加密
     * @param $binary string 加密的内容
     * @param $newPath string 保存文件地址
     * @return true
     */
    protected function encryPath($tmpName,$newPath)
    {
        try {
            $binary = file_get_contents($tmpName);
            $encryptedFileContent = openssl_encrypt($binary, 'AES-256-CBC', $this->key, 0, $this->iv);
            file_put_contents($newPath, $encryptedFileContent);
            return true;
        }catch (\Exception $e){
            throw new \Exception('上传失败');
        }
    }

    /**
     * 文件解密
     * @param $newPath
     * @param $ext
     * @return string
     */
    public function decryptPath($newPath,$ext = 'png')
    {
        if(!file_exists($newPath)){
            return $this->default_img;
        }
        $fileContent = file_get_contents($newPath);
        if(!$fileContent){
            return $this->default_img;
        }
        $decryptedFileContent = openssl_decrypt($fileContent, 'AES-256-CBC', $this->key, 0, $this->iv);
        return 'data:image/'.$ext.';base64,'.base64_encode($decryptedFileContent);
    }
}