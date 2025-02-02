<!DOCTYPE html>
<html>
<!--
 功能：1.登录成功时，将所有该用户的帐号数据（account表）加载到session，以避免之后对数据库中账户信息的反复查询
       2.出错跳转到本页面会显示“你无权访问”
       3.根据用户身份跳转到不同页面
       1.由'loginaction.php'在登录成功时跳转到本页
 接口：1.$_GET{'sid']
       2.需要获取$_SESSION['username']
       3.依据身份跳转到student.html, teacher.html


 -->
<head>
    <title>登录成功</title>
    <meta name="content-type" charset=UTF-8">
</head>
<body>
<div>
    <?php
    header("content-type:text/html;charset=utf-8");
    //-----------------连接mysql服务器----------------------------------------------------------
    require '../all/mysqllink.php';
    //获取sid
    $sid = $_GET['sid'];
    session_id($sid);
    //开启session
    session_start();
    //获取变量
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
    //判断session是否为空
    if (!empty($username)) {
        ?>
        <h1>登录成功！</h1>
        欢迎您！
        <?php
        echo $username;
        //-----------------获取所有用户信息，存入session-----------------------------------------
        $query_select = "SELECT * FROM account WHERE username='$username' limit 1";
        $ret = mysqli_query($link, $query_select);
        $row = mysqli_fetch_assoc($ret);
        //将信息存储到session
        foreach ($row as $key => $value) {
            $_SESSION[$key] = $value;
        }
        //根据用户身份跳转到不同页面
        if ($_SESSION['role'] == 'student') {
            header("Location:../student/demo/index.html?sid=" . $sid);
        } elseif ($_SESSION['role'] == "tutor") {
            header("Location:../tutor/tutor1.html?sid=" . $sid);
        }

        ?>
        <br/>
        <?php
    } else {
        //未登录，无权访问
        ?>
        <h1>你无权访问！！！</h1>
        <?php
    }
    ?>
</div>
</body>
</html>
