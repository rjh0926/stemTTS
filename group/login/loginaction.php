<?php
/*
功能：1.判断登录是否成功
      2.根据'记住我'选项设置cookie保存邮箱
    3.登录成功时跳转到loginsucc.php
    4.登录失败时跳转回login.php并用$_GET['err']传递错误码
接口:1.需要获得$_POST['emailaddress']，$_POST['password']，$_POST['remember']
    4.按照sid+user的方式生成sid，例： sid12
 */
header("content-type:text/html;charset=utf-8");

//-----------------mysql参数----------------------------------------------
$servername = "localhost";
$usern = "root";
$passw = "root";
$dbname = "db2";

//声明变量
$emailaddress = isset($_POST['emailaddress'])?$_POST['emailaddress']:"";
$password = isset($_POST['password'])?$_POST['password']:"";
$remember = isset($_POST['remember'])?$_POST['remember']:"";
//判断用户名和密码是否为空
if(!empty($emailaddress)&&!empty($password)) {
    //建立连接
    $link =mysqli_connect($servername,$usern ,$passw);
    $res=mysqli_set_charset($link,'utf8');
    //选择数据库
    mysqli_query($link,'use '.$dbname);
    //准备SQL语句
    $query_select = "SELECT emailaddress,password,userid FROM account WHERE emailaddress = '$emailaddress' AND password = '$password' limit 1";
    //执行SQL语句
    $ret = mysqli_query($link,$query_select);
    $row = mysqli_fetch_array($ret);
    //-----------------获取接口变量----------------------------------------------
    $userid=$row['userid'];
    $sid="sid".$userid;
    session_id($sid);
    session_start();
    $_SESSION['emailaddress']=$emailaddress;

    //判断用户名或密码是否正确
    if($emailaddress==$row['emailaddress']&&$password==$row['password']) {
        //选中“记住我”
        if($remember=="on") {
            //创建cookie
            setcookie("emailaddress", $emailaddress, time()+7*24*3600);
        }
        //跳转到loginsucc.php页面
        $destination="Location:loginsucc.php?sid=".$sid;
        header($destination);
        //关闭数据库
        mysqli_close($link);
    }else {
        //用户名或密码错误，赋值err为1
        header("Location:login.php?err=1");
    }
}else {
    //用户名或密码为空，赋值err为2
    header("Location:login.php?err=2");
}

