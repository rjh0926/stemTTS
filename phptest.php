<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "db2";

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
}

$sql = "SELECT username FROM account";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // 输出数据
    while($row = mysqli_fetch_assoc($result)) {
        echo "Name: " . $row["username"]. "<br>";
    }
} else {
    echo "0 结果";
}

mysqli_close($conn);
?>