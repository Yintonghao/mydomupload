<?php

/**

 * PHP获取路径或目录实现

 */

//$_SERVER['PHP_SELF']和$_SERVER['SCRIPT_NAME']的结果一般相同，他们都是获取当前脚本的文件名

//只有当php以cgi方式运行时有区别，但是现在几乎找不到以cgi方式运行php了
//
//echo '$_SERVER["PHP_SELF"]: ========> '.$_SERVER['PHP_SELF'];
//
//echo '<br/>';
//
//
//
//echo '$_SERVER["SCRIPT_NAME"]: ========> '.$_SERVER['SCRIPT_NAME'];
//
//echo '<br/>';
//
//
//
////当前执行脚本的绝对路径。记住，在CLI方式运行php是获取不到的
//
//echo '$_SERVER["SCRIPT_FILENAME"]: ========> '.$_SERVER['SCRIPT_FILENAME'];
//
//echo '<br/>';
//
//
//
////当前运行脚本所在的文档根目录。在服务器配置文件中定义。
//
//echo '$_SERVER["DOCUMENT_ROOT"]: ========> '.$_SERVER['DOCUMENT_ROOT'];
//
//echo '<br>';
//
//
//
////getcwd()返回当前工作目录
//
//echo "getcwd(): ========> ".getcwd();
//
//echo '<br>';
//

//
//echo '<br>';
//
//echo "本文来自PHP中文网";
require "vendor/autoload.php";


echo '<br>';

$file = $_FILES['file'];
echo "<pre>";
var_dump($file);

$upload = new Mydom\Uploads\Signature('uploads','images',['gif']);

$upload->encryptUpload([$file]);