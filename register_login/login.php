<!DOCTYPE html>
<html>
<!--
功能：1.登陆界面
    2.提供‘记住我’选项，提示后台将邮箱保存到cookie
    3.在登录失败是根据错误码显示失败原因
    4.提供向注册页面的跳转
接口：1.点击登录按钮后跳转到loginaction.php,并提供$_POST['emailaddress']，$_POST['password']，$_POST['remember']
    2.检查$_GET['err'],根据错误码显示登录失败原因
    3.点击‘注册’按钮跳转到注册页面
-->
<head>
    <title>登录</title>
    <meta name="content-type" charset=UTF-8">
</head>
<body>
<div class="col-md-8 column" style="margin-left: 5%">
    <h1>
        STEM虚拟实习教师
    </h1>
</div>

<div class="content" align="center" style="height: 500px">

    <!--头部-->
    <div class="header">
        <h2>登录</h2>
    </div>
    <!--中部-->
    <div class="middle">
        <form id="loginform" action="loginaction.php" method="post">
            <table border="0">
                <tr>
                    <td>用 户 名：</td>
                    <td>
                        <input id="email" name="username" required="required"
                               value="<?php echo isset($_COOKIE["username"]) ? $_COOKIE["username"] : ""; ?>">
                    </td>
                </tr>
                <tr>
                    <td>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：</td>
                    <td><input type="password" id="password" name="password"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="checkbox" name="remember">
                        <small>记住我
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center" style="color:red;font-size:10px;">
                        <!--提示信息-->
                        <?php
                        header("content-type:text/html;charset=utf-8");
                        $err = isset($_GET["err"]) ? $_GET["err"] : "";
                        switch ($err) {
                            case 1:
                                echo "用户名或密码错误！";
                                break;
                            case 2:
                                echo "用户名或密码不能为空！";
                                break;
                            case 3:
                                echo "<script language=\"JavaScript\">alert(\"请等待老师完成分班\");</script>";
                                break;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" id="login" name="login" value="登录">
                        <input type="reset" id="reset" name="reset" value="重置">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        还没有账号，快去<a href="register.php">注册</a>吧！
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<!--脚部-->
<div class="footer" align="center">
    <small>Copyright &copy; 版权所有·欢迎翻版
</div>

</body>
</html>