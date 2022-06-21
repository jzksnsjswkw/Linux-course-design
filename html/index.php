<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生成绩管理</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="icon/iconfont.css">
</head>

<body>
    <?php
    if (empty($_COOKIE['userid'])) {
        echo '<script>window.alert("请登录");window.location.href="login.php";</script>';
        return false;
    }
    ?>

    <?php
    $pdo = new PDO('mysql:host=localhost;dbname=xscj', 'root', 'a12345678');
    $pdo->query('SET NAMES UTF8');
    ?>

    <?php
    $stmt = $pdo->prepare("SELECT * FROM xs");
    $stmt->execute();
    $lists = $stmt->fetchAll();
    ?>

    <div class="container">
        <header>
            <h1>
                <a href="https://www.hzu.edu.cn/" target="_blank">
                    <img src="img/logo-mini.png">
                </a>
            </h1>
            <form action="login.php" method="post">
                <input type="submit" name="exit" value="退出登录">
            </form>
        </header>
        <div class="main">
            <div class="left">
                <img src="img/mysql.jpg">
                <div>学生成绩管理系统</div>
                <ul>
                    <?php
                    if ($_GET['class'] == 1)
                        echo '<li class="check">';
                    else
                        echo '<li>';
                    ?>
                    <a href="index.php?class=1">
                        <i class="iconfont icon-geren"></i>
                        <div>学生</div>
                    </a>
                    </li>
                    <?php
                    if ($_GET['class'] == 2)
                        echo '<li class="check">';
                    else
                        echo '<li>';
                    ?>
                    <a href="index.php?class=2">
                        <i class="iconfont icon-chengji"></i>
                        <div>成绩</div>
                    </a>
                    </li>
                    <?php
                    if ($_GET['class'] == 3)
                        echo '<li class="check">';
                    else
                        echo '<li>';
                    ?>
                    <a href="index.php?class=3">
                        <i class="iconfont icon-kebiao"></i>
                        <div>课程</div>
                    </a>
                    </li>
                </ul>
            </div>
            <div class="right">
                <?php
                if ($_GET['class'] == 1) {
                ?>
                    <div class="student">
                        <ul class="insert">
                            <form action="upload.php" method='post' enctype="multipart/form-data">
                                <div>录入&更新</div>
                                <li>
                                    姓名
                                    <input class="text" type="text" maxlength="12" name="name">
                                </li>
                                <li class="sex">
                                    性别
                                    <input class="radio" type="radio" name="sex" value="1">
                                    男
                                    <input class="radio" type="radio" name="sex" value="0">
                                    女
                                </li>
                                <li class="birthday">
                                    出生年月
                                    <input class="text" type="date" name="birthday">
                                </li>
                                <li class="photo">
                                    照片
                                    <input type="file" class="file" name="photo">
                                </li>
                                <input class="submit" type="submit" name="student" value="录入">
                                <input class="submit" type="submit" name="student" value="更新">
                            </form>
                        </ul>
                        <?php
                        if (empty($_POST['name'])) {
                            if ($_POST['student'] == '查询' || $_POST['student'] == '删除')
                                echo '<script>window.alert("请输入姓名");history.back();</script>';
                        ?>
                            <ul class="search">
                                <form action="index.php?class=1" method="post">
                                    <div>查询&删除</div>
                                    <li>
                                        姓名
                                        <input class="text" type="text" maxlength="12" name="name">
                                    </li>
                                    <input class="submit" type="submit" name="student" value="查询">
                                    <input class="submit" type="submit" name="student" value="删除">
                                </form>
                            </ul>
                            <?php
                        } else {
                            $stmt = $pdo->prepare('SELECT * FROM xs WHERE name="' . $_POST['name'] . '"');
                            $stmt->execute();
                            $name = $stmt->fetchAll();
                            $find = $name['0'];
                            if (empty($find)) {
                                echo '<script>window.alert("未找到该生");history.back();</script>';
                                return false;
                            } elseif ($_POST['student'] == '查询') {
                            ?>
                                <ul class="result">
                                    <div>查询结果</div>
                                    <li>
                                        姓名:
                                        <?php echo $find['name']; ?>
                                    </li>
                                    <li>
                                        性别:
                                        <?php
                                        if ($find['sex'] == 1)
                                            echo '男';
                                        else
                                            echo '女';
                                        ?>
                                    </li>
                                    <?php if ($find['birthday']) {
                                        echo '<li>';
                                        echo '出生年月:';
                                        echo $find['birthday'];
                                        echo '</li>';
                                    }
                                    ?>
                                    <?php if ($find['photo']) {
                                        echo '<li>';
                                        echo '照片:';
                                        echo '<img src="./photo/' . $find['photo'] . '">';
                                        echo '</li>';
                                    }
                                    ?>
                                    <li>
                                        已修课程数:
                                        <?php
                                        echo $find['courseNumber'];
                                        ?>
                                    </li>
                                    <a class="return" href="index.php?class=1">返回</a>
                                </ul>
                        <?php
                            } else {
                                $file = "./photo/" . $find['photo'];
                                unlink($file);
                                $stmt = $pdo->prepare('DELETE FROM xs WHERE name="' . $find['name'] . '"');
                                $stmt->execute();
                                $stmt = $pdo->prepare('DELETE FROM cj WHERE name="' . $find['name'] . '"');
                                $stmt->execute();
                                echo '<script>window.alert("删除成功");window.location.href="index.php?class=1";</script>';
                            }
                        }
                        ?>
                    </div>
                <?php
                } elseif ($_GET['class'] == 2) {
                ?>
                    <div class="score">
                        <ul class="insert">
                            <form action="upload.php" method="post">
                                <div>录入&更新</div>
                                <li class="course-list">
                                    <div class="coursename">课程名</div>
                                    <select name="course" class="text">
                                        <?php
                                        $stmt = $pdo->prepare("SELECT course FROM kc");
                                        $stmt->execute();
                                        $list = $stmt->fetchAll();
                                        foreach ($list as $list_v) {
                                        ?>
                                            <option value="<?php echo $list_v['course'] ?>"><?php echo $list_v['course'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    姓名
                                    <input class="text" type="text" maxlength="12" name="name">
                                </li>
                                <li>
                                    成绩
                                    <input class="text" type="text" maxlength="11" name="achievement">
                                </li>
                                <input class="submit" type="submit" name="score" value="录入">
                                <input class="submit" type="submit" name="score" value="更新">
                            </form>
                        </ul>

                        <?php
                        if (empty($_POST['name'])) {
                            if ($_POST['score'] == '查询')
                                echo '<script>window.alert("请输入姓名");history.back();</script>';
                        ?>
                            <ul class="search">
                                <form action="index.php?class=2" method="post">
                                    <div>查询</div>
                                    <li>
                                        姓名
                                        <input class="text" type="text" maxlength="12" name="name">
                                    </li>
                                    <input class="submit" type="submit" name="score" value="查询">
                                </form>
                            </ul>
                            <?php
                        } else {
                            $stmt = $pdo->prepare('SELECT * FROM cj WHERE name="' . $_POST['name'] . '"');
                            $stmt->execute();
                            $name = $stmt->fetchAll();
                            if (empty($name)) {
                                echo '<script>window.alert("未找到该生");history.back();</script>';
                                return false;
                            } elseif ($_POST['score'] == '查询') {
                            ?>
                                <ul class="result">
                                    <div>查询结果</div>
                                    <li>
                                        姓名:
                                        <?php echo $name['0']['name']; ?>
                                    </li>
                                    <?php
                                    foreach ($name as $name_v) {
                                    ?>
                                        <li>
                                            课程名:
                                            <?php echo $name_v['course']; ?>
                                        </li>

                                        <li>
                                            成绩:
                                            <?php echo $name_v['achievement']; ?>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <a class="return" href="index.php?class=2">返回</a>
                                </ul>
                        <?php
                            }
                        }
                        ?>
                        <ul class="delete">
                            <form action="upload.php" method="post">
                                <div>删除</div>
                                <li class="course-list">
                                    <div class="coursename">课程名</div>
                                    <select name="course" class="text">
                                        <?php
                                        $stmt = $pdo->prepare("SELECT course FROM kc");
                                        $stmt->execute();
                                        $list = $stmt->fetchAll();
                                        foreach ($list as $list_v) {
                                        ?>
                                            <option value="<?php echo $list_v['course'] ?>"><?php echo $list_v['course'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    姓名
                                    <input class="text" type="text" maxlength="12" name="name">
                                </li>
                                <input class="submit" type="submit" name="score" value="删除">
                            </form>
                        </ul>
                    </div>


                <?php
                } elseif ($_GET['class'] == 3) {
                ?>

                    <div class="course">
                        <ul class="insert">
                            <form action="upload.php" method="post">
                                <div>录入&更新</div>
                                <li>
                                    <div class="coursename">课程名</div>
                                    <input class="text" type="text" maxlength="20" name="courseName">
                                </li>
                                <li>
                                    学时
                                    <input class="text" type="text" maxlength="4" name="creditHours">
                                </li>
                                <li>
                                    学分
                                    <input class="text" type="text" maxlength="4" name="credit">
                                </li>
                                <input class="submit" type="submit" name="course" value="录入">
                                <input class="submit" type="submit" name="course" value="更新">
                            </form>
                        </ul>
                        <?php
                        if (empty($_POST['course'])) {
                            if ($_POST['course'] == '查询')
                                echo '<script>window.alert("请输入姓名");history.back();</script>';
                        ?>
                            <ul class="search">
                                <form action="index.php?class=3" method="post">
                                    <div>查询</div>
                                    <li>
                                        课程名
                                        <input class="text" type="text" maxlength="20" name="courseName">
                                    </li>
                                    <input class="submit" type="submit" name="course" value="查询">
                                </form>
                            </ul>
                        <?php
                        } else {
                            $stmt = $pdo->prepare('SELECT * FROM kc WHERE course="' . $_POST['courseName'] . '"');
                            $stmt->execute();
                            $course = $stmt->fetchAll();
                            if (empty($course)) {
                                echo '<script>window.alert("未找到该门课程");history.back();</script>';
                                return false;
                            }
                            $find = $course['0'];
                        ?>
                            <ul class="result">
                                <div>查询结果</div>
                                <li>
                                    课程名:
                                    <?php echo $find['course']; ?>
                                </li>
                                <li>
                                    学时:
                                    <?php echo $find['creditHours']; ?>
                                </li>
                                <li>
                                    学分:
                                    <?php echo $find['credit']; ?>
                                </li>
                                <a href="index.php?class=3" class="return">返回</a>
                            </ul>
                        <?php
                        }
                        ?>
                        <ul class="delete">
                            <form action="upload.php" method="post">
                                <div>删除</div>
                                <li>
                                    课程名
                                    <input class="text" type="text" maxlength="20" name="courseName">
                                </li>
                                <input class="submit" type="submit" name="course" value="删除">
                            </form>
                        </ul>
                    </div>
                <?php
                } else {
                    echo '<script>window.location.href="login.php";</script>';
                }
                ?>
            </div>
        </div>
    </div>

    <footer>
        <!-- <div>
            系别、班级、姓名、QQ或邮箱等联系方式
        </div> -->
        <ul>
            <li>
                计算机科学与技术3班
            </li>
            <li>
                张昊然
            </li>
            <li>
                <i class="iconfont icon-qq"></i>
                214893178
            </li>
            <li>
                <a href="mailto:214893178@qq.com">
                    <i class="iconfont icon-youxiang01"></i>
                    214893178@qq.com
                </a>
            </li>

        </ul>
    </footer>
</body>

</html>