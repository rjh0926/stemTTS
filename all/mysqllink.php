<?php
//-----------------mysql参数----------------------------------------------
$servername = "localhost";
$usern = "root";
$passw = "root";
$dbname = "db2";
//-----------------连接mysql服务器----------------------------------------------
$link = mysqli_connect($servername,$usern ,$passw);;
$res = mysqli_set_charset($link, 'utf8');
//选择数据库
mysqli_query($link, 'use '.$dbname);
