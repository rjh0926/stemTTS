<?php
/*
功能：1.学生提交作业的后台处理
接口：1.从$_GET和$_SESSION,sid,见下方源代码‘获取接口变量’部分
        2.homework_history表
提示：有可移至前端完成的查询
*/

header('content-type:text/html;charset=utf-8');

//设置时区保证时间戳正确
date_default_timezone_set('PRC');
//-----------------获取接口变量----------------------------------------------
$sid=$_POST['sid'];
session_id($sid);
session_start();
$evaluation=$_POST['evaluation'];
$text=$_POST["text"];
$userid=$_SESSION['userid'];
$classid=$_SESSION['classid'];
$username=$_SESSION['username'];
$time=date('Y-m-d H:i:s',time());
$groupid=$_SESSION['groupid'];
$numberingroup=$_SESSION['numberingroup'];
$url='';
$urlname='';

//给出数据
//$file=$_FILES['image'];
$path='../upload';
$max_size=200000000;
$allow_type=array('image/jpg','image/jpeg','image/gif');
$allow_format=array('jpg','jpeg','gif');
$error='no error';


$url_arr=[];
//记录文件分享状态（shared字段）的字符串
$shared_str='';

foreach ($_FILES as $key => $value){
    $file=$value;
    if($file['name']==''){
        continue;
    }
    $urlname.=$file['name'].'@!';
    $temp='../upload/'.upload_single($file,$allow_type,$allow_format,$error,$path,$max_size);
    $url.=$temp.',';
    $url_arr[]=$temp;
    $shared_str.='0,';
}



//-----------------连接mysql服务器----------------------------------------------
require '../all/mysqllink.php';
//-----------------对应插入新纪录---------------------------------------------



//取得当前taskid，为防止taskid变动，不保存在session中，每次现查
$query="SELECT taskidnow FROM group_attr WHERE classid='$classid' AND groupid='$groupid' limit 1";
$ret=mysqli_query($link,$query);
$taskid_arr=mysqli_fetch_assoc($ret);
$taskidnow=$taskid_arr['taskidnow'];

//插入提交作业记录到log
$query="INSERT INTO log(timeStamp,classid,groupid,groupNO,userid,username,actiontype,taskid,content,url) VALUES ('$time','$classid','$groupid','$numberingroup'
          ,'$userid','$username','ReportSubmit','$taskidnow','$text','$url')";
mysqli_query($link,$query);

//更改作业状态记录
//如果评价是‘待修改’，修改旧记录
if($evaluation=='待修改'|| $evaluation=='未提交'){
    $query="UPDATE homework_mood SET evaluation='批改中' WHERE userid='$userid' AND taskid='$taskidnow' limit 1";
    mysqli_query($link,$query);
}
//插入新作业状态纪录
else{
    $query="INSERT INTO homework_mood VALUES('$userid','$taskidnow','批改中')";
    mysqli_query($link,$query);
}

//向report表插入记录
$query="UPDATE report SET classid='$classid',groupid='$groupid',groupNO='$numberingroup',userid='$userid',taskid='$taskidnow',content='$text',url='$url',urlname='$urlname',timeStamp='$time',shared='$shared_str' WHERE  userid='$userid' AND taskid='$taskidnow'";
mysqli_query($link,$query);

mysqli_close($link);
//返回前端需要的文件名信息（此处设计不合理，文件名不必要绕圈从后台返回，只是为了当时的方便
echo(json_encode($url_arr));

//参数为文件（$FILES中的项），允许的类型，允许的格式，错误，路径，允许的文件大小
//其中一些参数目前没有用到
//使用方法较难简洁的说明，请参照本文件使用实例，如遇到问题问题推荐重写
function upload_single($file,$allow_type,$allow_format=array(),$error,$path,$max_size){
    //判断文件是否有效
    //if(is_uploaded_file($file))
    if(!is_array($file)||!isset($file['error'])){
        $error='不是一个有效的上传文件';
        return false;
    }
    //判断存储路径是否有效
    if(!is_dir($path)){
        $error='上传路径不存在';
        return false;
    }
    //判断上传过程是否出错
    switch ($file['error']){
        case 1:
        case 2:
            $error='文件超出服务器大小';
            return false;
        case 3:
            $error='文件上传过程中出现问题 只上传了一部分';
            return false;
        case 4:
            $error='用户没有选择上传的文件';
            return false;
        case 6:
        case 7:
            $error='文件保存失败';
            return false;
    }
    //检查mime类型是否正确
    /*if (!in_array($file['type'],$allow_type)){
        $error='当前文件类型不允许上传';
        return false;
    }*/
    //检查文件扩展名
    $ext=ltrim(strrchr($file['name'],'.'),'.');
    /*if (empty($allow_format) && !in_array($ext,$allow_format)){
        $error='当前文件格式不允许上传';
        return false;
    }*/
    //构建文件名
    $fullname=strstr($file['name'],'/','true').date('Ymd');
    for ($i=0;$i<4;$i++){
        $fullname.=chr(mt_rand(65,90));
        $fullname.='.'.$ext;
    }
    //判断文件大小
    if($file['size']>$max_size){
        $error='上传文件过大';
        return false;
    }
    //移动文件
    if(!is_uploaded_file($file['tmp_name'])) {
        echo '不是上传文件';
        return false;
    }
    if (!move_uploaded_file($file['tmp_name'],$path.'/'.$fullname)){
        echo '文件移动失败';
        return false;
    }
    //echo 'upload succeed';
    return($fullname);
}



