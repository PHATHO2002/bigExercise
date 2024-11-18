<?php
session_start();
if (!$_SESSION['userName']) {
    header("Location: index.php");
}
require_once 'models\todolist.php';

use todoListBasic\Models\TodoList;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $userName = $_SESSION['userName'];
    echo "<p> Xin Chào  $userName </p>";
    ?>
    <form action="" method="POST">
        <input style="display: none;" type="text" name="logOut" value="1">
        <input type="submit" value="đăng xuất">
    </form>

    <?php
    $tasks = TodoList::getTasks($_SESSION['userName']);
    if (!empty($tasks)) {
        echo '  <h1 class="displayListBt"> danh sách công việc</h1>';
        echo  '<div style="display: flex;" class="listWork">';
        foreach ($tasks as $index => $task) {
            echo '  
        <div style="padding-left: 10px;" class="task-item">
        <h3>Title: ' . $task['title'] . '</h3>
        <p>Status: ' . $task['status'] . '</p>
        <p>Content: ' . $task['content'] . '</p>
        <button class="editButton" data-index=' . $index . ' >Sửa</button>
        <form action="" method="POST">
        <input style="display: none;"  name="indexDelete"   value="' . $task['indexIndata'] . '"/> 
        <button type="submit"  >Xóa</button>
        </form>
        <form action="" method="POST" data-index=' . $index . ' class="editForm" style="display: none; margin-top: 10px;">
            <input name="title"  type="text" value="' . $task['title'] . '"  />
            <input name="status"  type="text" value="' . $task['status'] . '"  />
            <input name="content"   value="' . $task['content'] . '"/> 
            <input style="display: none;"  name="indexUpdate"   value="' . $task['indexIndata'] . '"/> 
            <button type="submit"  >Lưu</button>
            <button data-index=' . $index . ' class="exitForm" >Hủy</button>
        </form>
      </div>';
        }
        echo '</div>';
    } else {
        echo "<h1> danh sách công việc bạn trống </h1>";
    }
    ?>
    <h1> Thêm công việc </h1>
    <form action="" method="POST">
        <div class="">
            <label for="">title</label>
            <input type="text" name="title">
        </div>
        <div class="">
            <label for="">status</label>
            <select name="status" id="example">
                <option value="incomplete">incomplete</option>
                <option value="completed">completed</option>

            </select>

        </div>
        <div class="">
            <label for="">content</label>
            <input type="text" name="content">
        </div>
        <input type="text" style="display: none;" name="addTask">
        <input type="submit" value="thêm">
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_POST["indexUpdate"])) {

            TodoList::editTask(
                $_POST["indexUpdate"],
                trim($_POST["title"]),
                trim($_POST["status"]),
                trim($_POST["content"])
            );
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else if (isset($_POST['indexDelete'])) {

            TodoList::removeTask($_POST['indexDelete']);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } elseif (isset($_POST['addTask'])) {
            $title = isset($_POST["title"]) ? trim($_POST["title"]) : null;
            $status = isset($_POST["status"]) ? trim($_POST["status"]) : null;
            $content = isset($_POST["content"]) ? trim($_POST["content"]) : null;


            if (empty($title) || empty($status) || empty($content)) {
                echo "Thiếu dữ liệu! Vui lòng nhập đầy đủ.";
            } else {

                TodoList::addTask($userName, $title, $status, $content);
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } elseif (isset($_POST['logOut'])) {
            session_destroy();
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    } ?>



</body>
<script>
    const editForms = document.querySelectorAll('.editForm')
    const editButtons = document.querySelectorAll('.editButton')
    const exitButtons = document.querySelectorAll('.exitForm')

    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {

            const index = button.getAttribute('data-index');

            editForms[index].style.display = 'flex';
            editForms[index].style.flexDirection = 'column';
        });
    });


    exitButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const index = button.getAttribute('data-index');
            editForms[index].style.display = 'none';
        });
    });
</script>

</html>