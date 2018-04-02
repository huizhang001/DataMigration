<?php
include_once 'config.php';
$modelDir = __DIR__ . '/model/';
includeAllPhpFile($modelDir);

use Medoo\Medoo;

$old_db = new Medoo($old_db_conf);
$new_db = new Medoo($new_db_conf);
function includeAllPhpFile($folder)
{

    foreach (glob("{$folder}/*.php") as $filename) {
        require_once $filename;
    }
}

/**
 * 产生随机字符串，不长于53位
 *
 * @param int $length 默认32位
 *
 * @return string
 */
function getNonceStr($length = 32)
{
    $length = $length > 53 ? 53 : $length;
    $str = "";
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    if ($length >= 17) {
        $length -= 17;
        $dateStr = date("YmdHis") . getMillisecond();
    }
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    $str = $dateStr . $str;
    return $str;
}

/**
 * microsecond 微秒     millisecond 毫秒
 * 返回时间戳的毫秒数部分
 *
 * @return float
 */
function getMillisecond()
{
    $a = microtime();
    $msec = substr($a . "", 2, 3);
    return $msec;
}

/**
 * 将dump出来的html格式保留
 *
 * @param mixed $obj 需要打印的对象
 *
 * @return string
 */
function dumpToHtml($obj)
{
    echo "<pre><xmp>";
    var_dump($obj);
    echo "</xmp></pre>";
}

/**
 * 将dump出来的字符串保存到变量中
 *
 * @param mixed $expression
 *
 * @return string
 */
function dumpToString($expression)
{

    ob_start();
    echo "<pre><xmp>";
    var_dump($expression);
    echo "</xmp></pre>";
    $var = ob_get_clean();
    return $var;
}