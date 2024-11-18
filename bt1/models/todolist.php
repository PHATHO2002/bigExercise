<?php

namespace todoListBasic\Models;

use todoListBasic\Models\Task;

require_once './models/taskClass.php';
class TodoList
{


    public static function addTask($userName, $title, $status, $content)
    {
        $newTask = new Task($title, $status, $content);
        $newTask->saveTask($userName);
    }
    public static function removeTask($index)
    {
        return Task::deleteTask($index);
    }
    public  static function editTask($index, $title = null, $status = null, $content = null)
    {
        return Task::editTask($index, $title, $status, $content);
    }
    public static function getTasks($userName)
    {
        $tasksJson = file_get_contents('./models/task.json');
        $tasks = json_decode($tasksJson, true);
        $userTasks = [];
        foreach ($tasks as $index => $task) {
            if (isset($task['userName']) && $task['userName'] === $userName) {
                $task['indexIndata'] = $index;
                $userTasks[] = $task;
            }
        }
        return $userTasks;
    }
}
