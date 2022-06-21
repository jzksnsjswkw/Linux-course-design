<?php
$pdo = new PDO('mysql:host=localhost;dbname=xscj', 'root', 'a12345678');
$pdo->query('SET NAMES UTF8');

//学生录入
if ($_POST['student'] == '录入') {
    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        echo '<script>window.alert("请输入姓名");window.location.href="index.php?class=1";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM xs WHERE name='$name'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (!empty($find)) {
        echo '<script>window.alert("该名学生已存在");window.location.href="index.php?class=1";</script>';
        return false;
    }

    if (isset($_POST['sex'])) {
        $sex = $_POST['sex'];
    } else {
        echo '<script>window.alert("请选择性别");window.location.href="index.php?class=1";</script>';
        return false;
    }

    if (isset($_POST['birthday'])) {
        $birthday = $_POST['birthday'];
    }
    if ($_FILES['photo']['tmp_name']) {  //判断是否上传图片

        $upload_file = $_FILES['photo']['tmp_name'];
        $store_dir = './photo/';
        $arr = explode('.', $_FILES['photo']['name']);
        $upload_file_name = time() . "$name." . end($arr);
        if (!move_uploaded_file($upload_file, $store_dir . $upload_file_name)) {
            echo '<script>window.alert("上传图片失败");window.location.href="index.php?class=1";</script>';
            return false;
        }

        if ($_POST['birthday']) {
            $stmt = $pdo->prepare("INSERT INTO xs VALUES ('$name', $sex, '$birthday', 0, NULL, '$upload_file_name' )");
            $stmt->execute();

            $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex and birthday = '$birthday' and photo = '$upload_file_name' ");
            $stmt->execute();
            $find = $stmt->fetchAll();
            if (empty($find)) {
                echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
                return false;
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO xs VALUES ('$name', $sex, NULL, 0, NULL, '$upload_file_name' )");
            $stmt->execute();

            $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex and photo = '$upload_file_name' ");
            $stmt->execute();
            $find = $stmt->fetchAll();
            if (empty($find)) {
                echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
                return false;
            }
        }
    } elseif ($_POST['birthday']) {
        $stmt = $pdo->prepare("INSERT INTO xs VALUES ('$name', $sex, '$birthday', 0, NULL, NULL )");
        $stmt->execute();

        $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex and birthday = '$birthday' ");
        $stmt->execute();
        $find = $stmt->fetchAll();
        if (empty($find)) {
            echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
            return false;
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO xs VALUES ('$name', $sex, NULL, 0, NULL, NULL )");
        $stmt->execute();

        $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex ");
        $stmt->execute();
        $find = $stmt->fetchAll();
        if (empty($find)) {
            echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
            return false;
        }
    }

    echo '<script>window.alert("录入成功");window.location.href="index.php?class=1";</script>';
    return true;
}



//学生更新

elseif ($_POST['student'] == '更新') {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        echo '<script>window.alert("请输入姓名");window.location.href="index.php?class=1";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM xs WHERE name='$name'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未找到该生");window.location.href="index.php?class=1";</script>';
        return false;
    }

    if (isset($_POST['sex'])) {
        $sex = $_POST['sex'];
    } else {
        echo '<script>window.alert("请选择性别");window.location.href="index.php?class=1";</script>';
        return false;
    }

    if (isset($_POST['birthday'])) {
        $birthday = $_POST['birthday'];
    }
    if ($_FILES['photo']['tmp_name']) {  //判断是否上传图片

        $upload_file = $_FILES['photo']['tmp_name'];
        $store_dir = './photo/';
        $arr = explode('.', $_FILES['photo']['name']);
        $upload_file_name = time() . "$name." . end($arr);
        if (!move_uploaded_file($upload_file, $store_dir . $upload_file_name)) {
            echo '<script>window.alert("上传图片失败");window.location.href="index.php?class=1";</script>';
            return false;
        }

        $file = "./photo/" . $find['0']['photo'];
        unlink($file);

        if ($_POST['birthday']) {
            $stmt = $pdo->prepare("UPDATE xs SET sex = $sex, birthday = '$birthday', photo = '$upload_file_name' WHERE name = '$name' ");
            $stmt->execute();

            $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex and birthday = '$birthday' and photo = '$upload_file_name' ");
            $stmt->execute();
            $find = $stmt->fetchAll();
            if (empty($find)) {
                echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
                return false;
            }
        } else {
            $stmt = $pdo->prepare("UPDATE xs SET sex = $sex, photo = '$upload_file_name' WHERE name = '$name' ");
            $stmt->execute();

            $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex and photo = '$upload_file_name' ");
            $stmt->execute();
            $find = $stmt->fetchAll();
            if (empty($find)) {
                echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
                return false;
            }
        }
    } elseif ($_POST['birthday']) {
        $stmt = $pdo->prepare("UPDATE xs SET sex=$sex, birthday='$birthday' WHERE name='$name' ");
        $stmt->execute();

        $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex and birthday = '$birthday' ");
        $stmt->execute();
        $find = $stmt->fetchAll();
        if (empty($find)) {
            echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
            return false;
        }
    } else {
        $stmt = $pdo->prepare(" UPDATE xs SET sex=$sex WHERE name='$name' ");
        $stmt->execute();

        $stmt = $pdo->prepare(" SELECT * FROM xs WHERE name = '$name' and sex = $sex ");
        $stmt->execute();
        $find = $stmt->fetchAll();
        if (empty($find)) {
            echo '<script>window.alert("未知错误");window.location.href="index.php?class=1";</script>';
            return false;
        }
    }

    echo '<script>window.alert("更新成功");window.location.href="index.php?class=1";</script>';
    return true;
}


//成绩录入
elseif ($_POST['score'] == '录入') {

    if (!empty($_POST['course'])) {
        $course = $_POST['course'];
    } else {
        echo '<script>window.alert("请选择课程");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        echo '<script>window.alert("请输入姓名");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name='$name' and course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (!empty($find)) {
        echo '<script>window.alert("该门课程已有成绩");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM xs WHERE name='$name'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("系统中无该名学生");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (isset($_POST['achievement'])) {
        $achievement = $_POST['achievement'];
    } else {
        echo '<script>window.alert("请输入成绩");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (!is_numeric($achievement)) {
        echo '<script>window.alert("成绩应为纯数字");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if ($achievement > 100 || $achievement < 0) {
        echo '<script>window.alert("成绩应在0-100之间");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("INSERT INTO cj VALUES ( '$name', '$course', $achievement )");
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name = '$name' and course = '$course' and achievement = $achievement ");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未知错误");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("UPDATE xs SET courseNumber=coursenumber + 1 WHERE name='$name'");
    $stmt->execute();
    echo '<script>window.alert("录入成功");window.location.href="index.php?class=2";</script>';
    return true;
}

//成绩更新
elseif ($_POST['score'] == '更新') {

    if (!empty($_POST['course'])) {
        $course = $_POST['course'];
    } else {
        echo '<script>window.alert("请选择课程");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        echo '<script>window.alert("请输入姓名");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name='$name' and course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("该名学生未修本课程");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name='$name'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未找到该生");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (isset($_POST['achievement'])) {
        $achievement = $_POST['achievement'];
    } else {
        echo '<script>window.alert("请输入成绩");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (!is_numeric($achievement)) {
        echo '<script>window.alert("成绩应为纯数字");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if ($achievement > 100 || $achievement < 0) {
        echo '<script>window.alert("成绩应在0-100之间");window.location.href="index.php?class=2";</script>';
        return false;
    }


    $stmt = $pdo->prepare("UPDATE cj SET achievement=$achievement WHERE name='$name' and course='$course'");
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name = '$name' and course ='$course' and achievement = $achievement ");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未知错误");window.location.href="index.php?class=2";</script>';
        return false;
    }

    echo '<script>window.alert("更新成功");window.location.href="index.php?class=2";</script>';
    return true;
}


//成绩删除
elseif ($_POST['score'] == '删除') {
    if (!empty($_POST['course'])) {
        $course = $_POST['course'];
    } else {
        echo '<script>window.alert("请选择课程");window.location.href="index.php?class=2";</script>';
        return false;
    }

    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        echo '<script>window.alert("请输入姓名");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name='$name'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未找到该生");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name='$name' and course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("该名学生未修本课程");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("DELETE FROM cj WHERE name='$name' and course='$course'");
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT * FROM cj WHERE name = '$name' and course = '$course' ");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (!empty($find)) {
        echo '<script>window.alert("未知错误");window.location.href="index.php?class=2";</script>';
        return false;
    }

    $stmt = $pdo->prepare("UPDATE xs SET courseNumber=coursenumber - 1 WHERE name='$name'");
    $stmt->execute();
    echo '<script>window.alert("删除成功");window.location.href="index.php?class=2";</script>';
    return true;
}

//课程录入
elseif ($_POST['course'] == '录入') {

    if (!empty($_POST['courseName'])) {
        $course = $_POST['courseName'];
    } else {
        echo '<script>window.alert("请输入课程名");window.location.href="index.php?class=3";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM kc WHERE course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (!empty($find)) {
        echo '<script>window.alert("该门课程已存在");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!empty($_POST['creditHours'])) {
        $creditHours = $_POST['creditHours'];
    } else {
        echo '<script>window.alert("请输入学时");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if ($creditHours < 0) {
        echo '<script>window.alert("学时应大于零");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!is_numeric($creditHours)) {
        echo '<script>window.alert("学时应为纯数字");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!empty($_POST['credit'])) {
        $credit = $_POST['credit'];
    } else {
        echo '<script>window.alert("请输入学分");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if ($creditHours < 0) {
        echo '<script>window.alert("学分应大于零");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!is_numeric($credit)) {
        echo '<script>window.alert("学分应为纯数字");window.location.href="index.php?class=3";</script>';
        return false;
    }

    $stmt = $pdo->prepare("INSERT INTO kc VALUES ( '$course', $creditHours, $credit )");
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT * FROM kc WHERE course = '$course' and creditHours = $creditHours and credit = $credit ");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未知错误");window.location.href="index.php?class=3";</script>';
        return false;
    }

    echo '<script>window.alert("录入成功");window.location.href="index.php?class=3";</script>';
    return true;
}


//课程更新
elseif ($_POST['course'] == '更新') {

    if (!empty($_POST['courseName'])) {
        $course = $_POST['courseName'];
    } else {
        echo '<script>window.alert("请输入课程名");window.location.href="index.php?class=3";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM kc WHERE course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("该门课程不存在");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!empty($_POST['creditHours'])) {
        $creditHours = $_POST['creditHours'];
    } else {
        echo '<script>window.alert("请输入学时");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if ($creditHours < 0) {
        echo '<script>window.alert("学时应大于零");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!is_numeric($creditHours)) {
        echo '<script>window.alert("学时应为纯数字");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!empty($_POST['credit'])) {
        $credit = $_POST['credit'];
    } else {
        echo '<script>window.alert("请输入学分");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if ($creditHours < 0) {
        echo '<script>window.alert("学分应大于零");window.location.href="index.php?class=3";</script>';
        return false;
    }

    if (!is_numeric($credit)) {
        echo '<script>window.alert("学分应为纯数字");window.location.href="index.php?class=3";</script>';
        return false;
    }

    $stmt = $pdo->prepare("UPDATE kc SET creditHours=$creditHours, credit=$credit WHERE course='$course'");
    $stmt->execute();

    $stmt = $pdo->prepare("SELECT * FROM kc WHERE course = '$course' and creditHours = $creditHours and credit = $credit ");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未知错误");window.location.href="index.php?class=3";</script>';
        return false;
    }

    echo '<script>window.alert("录入成功");window.location.href="index.php?class=3";</script>';
    return true;
}

//课程删除
elseif ($_POST['course'] == '删除') {

    if (!empty($_POST['courseName'])) {
        $course = $_POST['courseName'];
    } else {
        echo '<script>window.alert("请输入课程名");window.location.href="index.php?class=3";</script>';
        return false;
    }

    $stmt = $pdo->prepare("SELECT * FROM kc WHERE course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (empty($find)) {
        echo '<script>window.alert("未找到该门课程");window.location.href="index.php?class=3";</script>';
    }

    $stmt = $pdo->prepare("SELECT name FROM cj WHERE course='$course'");
    $stmt->execute();
    $find = $stmt->fetchAll();

    foreach ($find as $find_v) {
        $stmt = $pdo->prepare("UPDATE xs SET courseNumber=coursenumber - 1 WHERE name='" . $find_v['name'] . "'");
        $stmt->execute();
    }

    $stmt = $pdo->prepare("DELETE FROM cj WHERE course='$course'");
    $stmt->execute();

    $stmt = $pdo->prepare(" SELECT * FROM cj WHERE course = '$course' ");
    $stmt->execute();
    $find = $stmt->fetchAll();
    if (!empty($find)) {
        echo '<script>window.alert("未知错误");window.location.href="index.php?class=3";</script>';
        return false;
    }


    $stmt = $pdo->prepare("DELETE FROM kc WHERE course='$course'");
    $stmt->execute();
    echo '<script>window.alert("删除成功");window.location.href="index.php?class=3";</script>';
    return true;
}


echo '<script>window.location.href="index.php?class=1";</script>';
