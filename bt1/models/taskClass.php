<?php

namespace todoListBasic\Models;

class Task
{
    private $title;
    private $status;
    private $content;
    public function __construct($title, $status, $content)
    {

        $this->title = $title;
        $this->status = $status;
        $this->content = $content;
    }
    public function saveTask($userName)
    {
        $tasksJson = file_get_contents('./models/task.json');
        $tasks = json_decode($tasksJson, true);
        $tasks[] = ['userName' => $userName, 'title' => $this->title, 'status' => $this->status, 'content' => $this->content];
        file_put_contents('./models/task.json', json_encode($tasks));
        return 'thêm công việc thành công';
    }
    public static function editTask($index, $newTitle, $newStatus, $newContent)
    {

        $tasksJson = file_get_contents('./models/task.json');
        $tasks = json_decode($tasksJson, true);


        if (isset($tasks[$index])) {

            $tasks[$index] = [
                'userName' => $tasks[$index]['userName'],
                'title' => $newTitle ?? $tasks[$index]['title'],
                'status' => $newStatus ?? $tasks[$index]['status'],
                'content' => $newContent ?? $tasks[$index]['content']
            ];


            file_put_contents('./models/task.json', json_encode($tasks));

            return  "cập nhập thành công";
        } else {
            return "công việc không tồn tại";
        }
    }
    public static function deleteTask($index)
    {

        $tasksJson = file_get_contents('./models/task.json');
        $tasks = json_decode($tasksJson, true);

        if (isset($tasks[$index])) {
            unset($tasks[$index]);
            $tasks = array_values($tasks);
            file_put_contents('./models/task.json', json_encode($tasks));

            return 'Xóa công việc thành công';
        } else {
            return 'Công việc không tồn tại';
        }
    }
}
