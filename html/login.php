<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生成绩管理</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <?php
    if ($_POST['exit'] == '退出登录') {
        setcookie('userid', '');
        echo '<script>window.alert("退出成功");window.location.href="login.php";</script>';
        return true;
    } elseif (!empty($_COOKIE['userid'])) {
        echo '<script>window.location.href="index.php?class=1";</script>';
        return false;
    }

    if (!empty($_POST)) {

        if (empty($_POST['username'])) {
            echo '<script>window.alert("请输入账号");history.back();</script>';
            return false;
        }
        if (empty($_POST['password'])) {
            echo '<script>window.alert("请输入密码");history.back();</script>';
            return false;
        }

        $pdo = new PDO('mysql:host=localhost;dbname=xscj', 'root', 'a12345678');
        $pdo->query('SET NAMES UTF8');
        $stmt = $pdo->prepare('SELECT * FROM user WHERE username="' . $_POST['username'] . '"');
        $stmt->execute();
        $list = $stmt->fetchAll();
        $user = $list['0'];
        if (empty($user) || ($user['password'] != $_POST['password'])) {
            echo '<script>window.alert("账号或密码错误");history.back();</script>';
            return false;
        }

        if ($_POST['box'] == 'check')
            setcookie('userid', $user['userid'], time() + 604800);
        else
            setcookie('userid', $user['userid']);

        echo '<script>window.alert("登录成功");window.location.href="index.php?class=1";</script>';
        return false;
    }
    ?>
    <div class="bg"><img src="img/login-bg-autumn.jpg" alt=""></div>
    <h1 class="logo"><img src="img/login-logo.png" alt=""></h1>
    <ul class="other">
        <li>
            你可以使用以下方式登录
        </li>
        <li class="a wechat">
            <a class="icon" href="#"></a>
            <a class="text" href="#">使用微信登录</a>
            <div>没写</div>
        </li>
        <li class="a school">
            <a class="icon" href="#"></a>
            <a class="text" href="#">使用今日校园登录</a>
            <div>没写</div>
        </li>
    </ul>
    <form class="login" action="login.php" method="post">
        <div>账号登录</div>
        <div class="number">
            <div class="number-icon icon"></div>
            <input type="text" placeholder="用户名" name="username">
        </div>
        <div class="password">
            <div class="password-icon icon"></div>
            <input type="password" placeholder="密码" name="password">
        </div>
        <div class="free">
            <input type="checkbox" name="box" value="check">
            <div>一周内免登录</div>
        </div>
        <p>
            <input type="submit" value="登录" class="submit">
        </p>
    </form>

    <div class="footer">
        <div class="en">Copyright © 2018 HUIZHOU UNIVERSITY All Rights Reserved</div>
        <div class="zh"> 惠州学院</div>
    </div>
</body>

</html>