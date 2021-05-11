<?php


namespace RG;


use RG\Task;


class Log
{
    public function fail(Task $task)
    {
        $time = date('d.m.y H:i:s');

        $emails = implode(",",$task->destination) ?: 'пустой адрес';

        file_put_contents($_ENV['LOG'], "$time: <ОШИБКА> не удалось отправить письмо на $emails. Тема: $task->theme. Содержание: $task->content\n", FILE_APPEND);
    }
}