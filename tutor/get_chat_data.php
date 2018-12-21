<?php
/*
接口：1.教师对应小组数固定，通过$group_num设置
      2.从GET,session,见下方源代码‘获取接口变量’
        3.$_SESSION['group0'],$_SESSION['group1']...
*/
header("Content-Type:application/json");
//-----------------常量设置----------------------------------------------
//教师对应小组数
$group_num=4;

//-----------------获取接口变量----------------------------------------------
$sid=$_GET['sid'];
session_id($sid);
session_start();
$userid=$_SESSION['userid'];
$classid=$_GET['classid'];
$maxtimeStamp=$_GET['maxtimeStamp'];

//$chat_data数组用来存放聊天信息
$chat_data=array();


//-----------------连接mysql服务器----------------------------------------------
require '../all/mysqllink.php';


for($i=1;$i<=$group_num;$i++){

    $query="SELECT timeStamp,username,content FROM chat WHERE classid='$classid' AND groupid='$i' AND timeStamp>'$maxtimeStamp';";

    $result=mysqli_query($link,$query);

    $info = array();
    while($rst =mysqli_fetch_assoc($result)){
        $info[] = $rst;
    }
    $chat_data[$i]=$info;
    unset($info);
}
mysqli_close($link);
echo (json_encode($chat_data));



